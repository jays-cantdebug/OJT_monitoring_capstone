import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const PING_INTERVAL_MS = 60000;

Alpine.data('timeClock', (onDuty) => ({
    requesting: false,
    error: null,
    latitude: null,
    longitude: null,
    pingIntervalId: null,

    init() {
        if (onDuty) {
            this.pingIntervalId = setInterval(() => this.sendPing(), PING_INTERVAL_MS);
        }
    },

    requestLocationAndSubmit(formRef) {
        this.error = null;

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
            () => {
                this.requesting = false;
                this.error = 'Location access was denied. You must allow location access to time in or out.';
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

Alpine.data('liveMap', (initialOnDuty) => ({
    students: initialOnDuty,

    async init() {
        const { initEcho } = await import('./echo');

        initEcho()
            .private('dean.live-map')
            .listen('.ping.created', (event) => {
                const existing = this.students.find((student) => student.userId === event.user_id);

                if (existing) {
                    existing.latitude = event.latitude;
                    existing.longitude = event.longitude;
                    existing.lastPingAt = 'just now';
                }
            });
    },
}));

Alpine.start();
