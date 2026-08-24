import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const PING_INTERVAL_MS = 60000;

// A browser only exposes real Geolocation results on a "secure context"
// (https://, or exactly http://localhost / 127.0.0.1) - anywhere else,
// including a plain http:// LAN IP like a phone hitting the dev server
// over WiFi, getCurrentPosition() fails immediately with PERMISSION_DENIED
// and no prompt is ever shown. That reads to a student as "permission
// denied" with nothing to grant, so it's called out as its own case here
// rather than falling through to the generic permission-denied message.
const INSECURE_CONTEXT_MESSAGE = 'Location access requires a secure connection. This page must be loaded over HTTPS, or from "localhost" - a plain network address (like an IP address over HTTP) will not work. Please let your administrator know.';

function geolocationErrorMessage(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            return 'Location access was denied. You must allow location access to time in or out.';
        case error.TIMEOUT:
            return 'Getting your location timed out. Check your GPS/network signal and try again.';
        case error.POSITION_UNAVAILABLE:
            return 'Your location could not be determined. Check your GPS/network signal and try again.';
        default:
            return 'Could not get your location. Please try again.';
    }
}

Alpine.data('timeClock', () => ({
    requesting: false,
    error: null,
    latitude: null,
    longitude: null,
    insecureContext: !window.isSecureContext,

    requestLocationAndSubmit(formRef) {
        this.error = null;

        if (!window.isSecureContext) {
            this.error = INSECURE_CONTEXT_MESSAGE;
            return;
        }

        if (!navigator.geolocation) {
            this.error = 'Your browser does not support location access, which is required to time in or out.';
            return;
        }

        this.requesting = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                this.latitude = position.coords.latitude;
                this.longitude = position.coords.longitude;
                this.requesting = false;
                this.$nextTick(() => this.$refs[formRef].submit());
            },
            (error) => {
                this.requesting = false;
                this.error = geolocationErrorMessage(error);
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    },
}));

// Mounted once on the shared student layout (not the Time In/Out page)
// so the ping timer keeps running no matter which page the student is on
// while their DTR entry is open. Alpine state doesn't survive a full page
// navigation, so this restarts fresh on every page load - what it fixes is
// pings stopping *entirely* the moment the student leaves /student/time,
// not gaps at the exact moment of navigation.
Alpine.data('gpsTracker', (onDuty) => ({
    pingIntervalId: null,

    init() {
        if (onDuty) {
            this.pingIntervalId = setInterval(() => this.sendPing(), PING_INTERVAL_MS);
        }
    },

    destroy() {
        if (this.pingIntervalId) {
            clearInterval(this.pingIntervalId);
        }
    },

    // Fired every PING_INTERVAL_MS while on duty. Failures are logged but
    // never surfaced to the student - a missed location ping isn't worth
    // interrupting their screen for. The server independently re-derives
    // the open DTR entry and rejects the ping outright the instant the
    // student times out, so there's no need to stop this interval from the
    // client side either.
    sendPing() {
        if (!window.isSecureContext) {
            console.warn('GPS ping skipped: insecure context.', INSECURE_CONTEXT_MESSAGE);
            return;
        }

        if (!navigator.geolocation) {
            console.warn('GPS ping skipped: geolocation not supported by this browser.');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                window.axios.post('/student/gps-pings', {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                }).catch((error) => {
                    console.warn('GPS ping failed to send.', error);
                });
            },
            (error) => {
                console.warn('GPS ping skipped: could not get location.', geolocationErrorMessage(error));
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    },
}));

Alpine.data('photoPreview', () => ({
    previewUrl: null,
    fileName: null,
    dragging: false,

    onFileSelected(event) {
        this.setPreview(event.target.files?.[0] ?? null);
    },

    onDrop(event) {
        this.dragging = false;

        const file = event.dataTransfer.files?.[0] ?? null;

        if (file && file.type.startsWith('image/')) {
            this.$refs.input.files = event.dataTransfer.files;
        }

        this.setPreview(file);
    },

    // Object URLs (unlike FileReader's base64 data URLs) don't hold the
    // whole image in memory as a JS string - it matters here since reports
    // allow photos up to 5MB. The previous one is revoked before replacing
    // it so repeated file swaps don't leak memory for the life of the page.
    setPreview(file) {
        if (this.previewUrl) {
            URL.revokeObjectURL(this.previewUrl);
            this.previewUrl = null;
            this.fileName = null;
        }

        if (file && file.type.startsWith('image/')) {
            this.previewUrl = URL.createObjectURL(file);
            this.fileName = file.name;
        }
    },
}));

// Fallback map center (Cabadbaran City) used only until the first student
// location is known - real pings recenter/fit the map.
const DEFAULT_MAP_CENTER = [9.1236, 125.5350];

// Shared by liveMap (Dean) and myLocationMap (Student) so both maps render
// pixel-identical markers by construction, not by copy-pasted styling that
// can drift apart over time.
function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[char]));
}

function initials(name) {
    return escapeHtml((name ?? '').trim().charAt(0).toUpperCase());
}

function avatarHtml(person) {
    return person.avatarUrl
        ? `<img src="${escapeHtml(person.avatarUrl)}" alt="" class="block h-9 w-9 rounded-full border-2 border-white object-cover shadow-md">`
        : `<span class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-gold/10 text-sm font-bold text-gold shadow-md">${initials(person.name)}</span>`;
}

// The dot's color encodes whether this position is a real-time ping
// (success, green) or a fallback coordinate because no live update is
// currently available (warning, amber) - see hasLivePing.
function markerIcon(L, person) {
    const dotColor = person.hasLivePing ? 'bg-success' : 'bg-warning';

    return L.divIcon({
        className: '',
        html: `<span class="relative block h-9 w-9">
            ${avatarHtml(person)}
            <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full ${dotColor} ring-2 ring-white"></span>
        </span>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
    });
}

// Used by locationLightbox for the Attendance History "View Location" map -
// a plain colored pin, not an avatar-based marker like markerIcon() above.
// This isn't "a person's live position," it's "where a past event
// happened," so it deliberately gets its own simpler visual treatment
// rather than forcing the avatar marker to mean something it doesn't here.
function eventMarkerIcon(L, label, colorClass) {
    return L.divIcon({
        className: '',
        html: `<span class="flex h-8 w-8 items-center justify-center rounded-full border-2 border-white ${colorClass} text-[10px] font-bold text-white shadow-md">${escapeHtml(label)}</span>`,
        iconSize: [32, 32],
        iconAnchor: [16, 16],
    });
}

Alpine.data('liveMap', (initialOnDuty, department) => {
    // Leaflet's map/marker instances are kept out of Alpine's reactive
    // proxy (plain closure variables, not `this` properties) - wrapping
    // them in Alpine's reactivity is a known source of breakage.
    let map = null;
    const markers = {};

    // Tracks which student the search box last flew the map to, so typing
    // further characters that still resolve to the same single match
    // doesn't replay the fly-to/pulse on every keystroke.
    let highlightedUserId = null;

    function profileUrl(student) {
        return `/dean/students/${student.userId}`;
    }

    function matchesSearch(student, rawTerm) {
        const term = (rawTerm ?? '').trim().toLowerCase();

        return term === '' || student.name.toLowerCase().includes(term);
    }

    function popupHtml(student) {
        const locationNote = student.hasLivePing
            ? ''
            : '<p class="mt-1 text-xs text-warning">Time In location &mdash; awaiting first live update</p>';

        return `<p class="font-semibold text-navy">${escapeHtml(student.name)}</p>
            ${locationNote}
            <a href="${profileUrl(student)}" class="text-xs font-medium text-navy hover:underline">View Profile &rarr;</a>`;
    }

    // Every on-duty student now always has a coordinate - either a real
    // ping or the Time In fallback from the controller - so this no longer
    // needs to guard against missing lat/lng. Existing markers also refresh
    // their icon and popup, not just position, so a marker visibly flips
    // from pending (amber) to live (green) the moment its first ping lands.
    function upsertMarker(L, student) {
        const latLng = [student.latitude, student.longitude];

        if (markers[student.userId]) {
            markers[student.userId]
                .setLatLng(latLng)
                .setIcon(markerIcon(L, student))
                .setPopupContent(popupHtml(student));
        } else {
            markers[student.userId] = L.marker(latLng, { icon: markerIcon(L, student) })
                .bindPopup(popupHtml(student));
        }
    }

    // Briefly rings the marker's icon element so a search match is obvious
    // even once the map has already flown to it. Removed and re-added
    // (with a reflow forced in between) rather than just added, so the
    // animation restarts cleanly if the same marker is highlighted again.
    function highlightMarker(marker) {
        const el = marker.getElement();

        if (!el) {
            return;
        }

        el.classList.remove('marker-pulse');
        void el.offsetWidth;
        el.classList.add('marker-pulse');
    }

    return {
        students: initialOnDuty,
        search: '',

        get filteredStudents() {
            return this.students.filter((student) => matchesSearch(student, this.search));
        },

        profileUrl,

        // Frames every on-duty student (or the campus default when there
        // are none) - used on initial load and again whenever the search
        // box is cleared, so clearing search reads as "back out to the
        // overview" rather than leaving the view wherever the last search
        // happened to leave it.
        resetView() {
            if (this.students.length > 1) {
                map.flyToBounds(this.students.map((student) => [student.latitude, student.longitude]), {
                    padding: [32, 32],
                });
            } else if (this.students.length === 1) {
                map.flyTo([this.students[0].latitude, this.students[0].longitude], 15);
            } else {
                map.flyTo(DEFAULT_MAP_CENTER, 15);
            }
        },

        // Markers are created eagerly for every on-duty student (see
        // upsertMarker) but only added to / removed from the map here, so
        // the map's visible pins always match the filtered list below it.
        syncMarkerVisibility() {
            this.students.forEach((student) => {
                const marker = markers[student.userId];

                if (!marker) {
                    return;
                }

                const shouldShow = matchesSearch(student, this.search);
                const isShown = map.hasLayer(marker);

                if (shouldShow && !isShown) {
                    marker.addTo(map);
                } else if (!shouldShow && isShown) {
                    map.removeLayer(marker);
                }
            });
        },

        async init() {
            const L = await import('leaflet');
            const { initEcho } = await import('./echo');

            map = L.map(this.$refs.map).setView(
                this.students.length ? [this.students[0].latitude, this.students[0].longitude] : DEFAULT_MAP_CENTER,
                15
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            this.students.forEach((student) => upsertMarker(L, student));
            this.syncMarkerVisibility();
            this.resetView();

            // A search that narrows to exactly one student flies the map to
            // them and gives their marker a brief highlight pulse. Ties and
            // empty results are left alone rather than guessing which match
            // the Dean meant; clearing the box back to '' hands off to
            // resetView() instead, via the dedicated branch below.
            this.$watch('search', () => {
                this.syncMarkerVisibility();

                if (this.search.trim() === '') {
                    highlightedUserId = null;
                    this.resetView();
                    return;
                }

                if (this.filteredStudents.length !== 1) {
                    return;
                }

                const match = this.filteredStudents[0];

                if (match.userId === highlightedUserId) {
                    return;
                }

                highlightedUserId = match.userId;

                const marker = markers[match.userId];

                if (!marker) {
                    return;
                }

                map.flyTo([match.latitude, match.longitude], 17);
                marker.openPopup();
                highlightMarker(marker);
            });

            initEcho()
                .private(`dean.live-map.${department}`)
                .listen('.ping.created', (event) => {
                    const existing = this.students.find((student) => student.userId === event.user_id);

                    if (existing) {
                        existing.latitude = event.latitude;
                        existing.longitude = event.longitude;
                        existing.lastPingAt = 'just now';
                        existing.hasLivePing = true;
                        upsertMarker(L, existing);
                    } else {
                        // A student who came on duty after this page loaded -
                        // not part of the initial payload, so add them now
                        // instead of silently dropping their first ping.
                        const student = {
                            userId: event.user_id,
                            name: event.name,
                            avatarUrl: event.avatarUrl,
                            since: event.since,
                            latitude: event.latitude,
                            longitude: event.longitude,
                            lastPingAt: 'just now',
                            hasLivePing: true,
                        };
                        this.students.push(student);
                        upsertMarker(L, student);
                    }

                    this.syncMarkerVisibility();
                });
        },
    };
});

// A student's own position, unlike the Dean's Live Map, never needs the
// Reverb broadcast channel (that channel is deliberately Dean-only, see
// routes/channels.php) - it's driven straight off the browser's own
// geolocation, the same source the existing ping loop already uses. The
// marker itself reuses the exact same markerIcon()/avatarHtml() helpers as
// liveMap so the two maps render identically, not just similarly.
Alpine.data('myLocationMap', (me, lastKnownLocation, onDuty) => {
    let map = null;
    let marker = null;
    let watchId = null;

    function popupHtml(hasLivePing) {
        const locationNote = hasLivePing
            ? ''
            : '<p class="mt-1 text-xs text-warning">Last known location &mdash; not currently live</p>';

        return `<p class="font-semibold text-navy">${escapeHtml(me.name)}</p>${locationNote}`;
    }

    return {
        error: null,

        async init() {
            const L = await import('leaflet');

            const start = lastKnownLocation
                ? [lastKnownLocation.latitude, lastKnownLocation.longitude]
                : DEFAULT_MAP_CENTER;

            map = L.map(this.$refs.map).setView(start, 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            // Starts amber (not live) until the first successful
            // watchPosition callback flips it green - mirrors the Dean map's
            // amber-to-green flip when a student's first ping arrives.
            marker = L.marker(start, { icon: markerIcon(L, { ...me, hasLivePing: false }) })
                .bindPopup(popupHtml(false))
                .addTo(map);

            if (!onDuty) {
                return;
            }

            if (!window.isSecureContext) {
                this.error = INSECURE_CONTEXT_MESSAGE;
                return;
            }

            if (!navigator.geolocation) {
                this.error = 'Your browser does not support location access.';
                return;
            }

            watchId = navigator.geolocation.watchPosition(
                (position) => {
                    this.error = null;
                    const latLng = [position.coords.latitude, position.coords.longitude];
                    marker
                        .setLatLng(latLng)
                        .setIcon(markerIcon(L, { ...me, hasLivePing: true }))
                        .setPopupContent(popupHtml(true));
                    map.setView(latLng);
                },
                (error) => {
                    this.error = geolocationErrorMessage(error);
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        destroy() {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
            }
        },
    };
});

// One shared modal instance for the whole Attendance History page rather
// than one Leaflet map per row - show() re-centers/re-pins the same map
// instance on whichever entry was clicked. The map is created lazily on
// the first open (Leaflet needs a visible, correctly-sized container,
// which the modal's div only has once x-show has actually revealed it)
// and then left alive for subsequent opens rather than torn down each
// time, avoiding Leaflet's "container is already initialized" error.
Alpine.data('locationLightbox', () => {
    let map = null;
    let timeInMarker = null;
    let timeOutMarker = null;

    return {
        open: false,
        date: null,
        hasTimeOut: false,

        async show(entry) {
            this.date = entry.date;
            this.hasTimeOut = entry.timeOut !== null;
            this.open = true;

            await this.$nextTick();

            const L = await import('leaflet');

            if (!map) {
                map = L.map(this.$refs.map);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }).addTo(map);
            } else {
                map.invalidateSize();
            }

            if (timeInMarker) {
                map.removeLayer(timeInMarker);
            }
            if (timeOutMarker) {
                map.removeLayer(timeOutMarker);
                timeOutMarker = null;
            }

            const timeInLatLng = [entry.timeIn.lat, entry.timeIn.lng];
            timeInMarker = L.marker(timeInLatLng, { icon: eventMarkerIcon(L, 'IN', 'bg-success') })
                .bindPopup(entry.timeIn.label)
                .addTo(map);

            if (entry.timeOut) {
                const timeOutLatLng = [entry.timeOut.lat, entry.timeOut.lng];
                timeOutMarker = L.marker(timeOutLatLng, { icon: eventMarkerIcon(L, 'OUT', 'bg-navy') })
                    .bindPopup(entry.timeOut.label)
                    .addTo(map);

                map.flyToBounds([timeInLatLng, timeOutLatLng], { padding: [40, 40], maxZoom: 17 });
            } else {
                map.setView(timeInLatLng, 16);
            }
        },

        close() {
            this.open = false;
        },
    };
});

Alpine.start();
