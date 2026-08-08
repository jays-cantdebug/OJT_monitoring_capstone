import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('timeClock', () => ({
    requesting: false,
    error: null,
    latitude: null,
    longitude: null,

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
}));

Alpine.start();
