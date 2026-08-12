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

Alpine.data('timeClock', (onDuty) => ({
    requesting: false,
    error: null,
    latitude: null,
    longitude: null,
    pingIntervalId: null,
    insecureContext: !window.isSecureContext,

    init() {
        if (onDuty) {
            this.pingIntervalId = setInterval(() => this.sendPing(), PING_INTERVAL_MS);
        }
    },

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

    // Fired every PING_INTERVAL_MS while on duty. Silently skips a tick on
    // failure (denied permission, offline, etc.) rather than surfacing an
    // error — a missed location ping isn't worth interrupting the student's
    // screen for. The server independently re-derives the open DTR entry
    // and rejects the ping outright the instant the student times out, so
    // there's no need to stop this interval from the client side either.
    sendPing() {
        if (!navigator.geolocation) {
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                window.axios.post('/student/gps-pings', {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                }).catch(() => {});
            },
            () => {},
            { enableHighAccuracy: true, timeout: 10000 }
        );
    },
}));

// Fallback map center (NORMI campus, Cagayan de Oro) used only until the
// first student location is known - real pings recenter/fit the map.
const DEFAULT_MAP_CENTER = [8.4822, 124.6472];

Alpine.data('liveMap', (initialOnDuty) => {
    // Leaflet's map/marker instances are kept out of Alpine's reactive
    // proxy (plain closure variables, not `this` properties) - wrapping
    // them in Alpine's reactivity is a known source of breakage.
    let map = null;
    const markers = {};

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

    function avatarHtml(student) {
        return student.avatarUrl
            ? `<img src="${escapeHtml(student.avatarUrl)}" alt="" class="block h-9 w-9 rounded-full border-2 border-white object-cover shadow-md">`
            : `<span class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-white bg-gold/10 text-sm font-bold text-gold shadow-md">${initials(student.name)}</span>`;
    }

    // Every student on this map is already on-duty by definition (the
    // controller only ever queries on-duty students) - the dot is a visual
    // echo of that fact, matching the "on duty" dot used elsewhere in the
    // app, not new information.
    function markerIcon(L, student) {
        return L.divIcon({
            className: '',
            html: `<span class="relative block h-9 w-9">
                ${avatarHtml(student)}
                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-success ring-2 ring-white"></span>
            </span>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
        });
    }

    function profileUrl(student) {
        return `/dean/students/${student.userId}`;
    }

    function matchesSearch(student, rawTerm) {
        const term = (rawTerm ?? '').trim().toLowerCase();

        return term === '' || student.name.toLowerCase().includes(term);
    }

    function popupHtml(student) {
        return `<p class="font-semibold text-navy">${escapeHtml(student.name)}</p>
            <a href="${profileUrl(student)}" class="text-xs font-medium text-navy hover:underline">View Profile &rarr;</a>`;
    }

    function upsertMarker(L, student) {
        if (student.latitude == null || student.longitude == null) {
            return;
        }

        const latLng = [student.latitude, student.longitude];

        if (markers[student.userId]) {
            markers[student.userId].setLatLng(latLng);
        } else {
            markers[student.userId] = L.marker(latLng, { icon: markerIcon(L, student) })
                .bindPopup(popupHtml(student));
        }
    }

    return {
        students: initialOnDuty,
        search: '',

        get filteredStudents() {
            return this.students.filter((student) => matchesSearch(student, this.search));
        },

        profileUrl,

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

            const withLocation = this.students.filter(
                (student) => student.latitude != null && student.longitude != null
            );

            map = L.map(this.$refs.map).setView(
                withLocation.length ? [withLocation[0].latitude, withLocation[0].longitude] : DEFAULT_MAP_CENTER,
                15
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            this.students.forEach((student) => upsertMarker(L, student));
            this.syncMarkerVisibility();

            if (withLocation.length > 1) {
                map.fitBounds(withLocation.map((student) => [student.latitude, student.longitude]), {
                    padding: [32, 32],
                });
            }

            this.$watch('search', () => this.syncMarkerVisibility());

            initEcho()
                .private('dean.live-map')
                .listen('.ping.created', (event) => {
                    const existing = this.students.find((student) => student.userId === event.user_id);

                    if (existing) {
                        existing.latitude = event.latitude;
                        existing.longitude = event.longitude;
                        existing.lastPingAt = 'just now';
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
                        };
                        this.students.push(student);
                        upsertMarker(L, student);
                    }

                    this.syncMarkerVisibility();
                });
        },
    };
});

Alpine.start();
