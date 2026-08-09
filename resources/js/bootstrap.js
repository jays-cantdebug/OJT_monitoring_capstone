import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Echo is intentionally NOT imported here. It's only needed on the Dean's
// live-map page, and only a Dean can ever authenticate on the
// 'dean.live-map' private channel — importing it globally would open a
// Reverb WebSocket connection from every Student's browser on every page
// load for a channel they can never subscribe to. See resources/js/echo.js
// (lazy factory) and the 'liveMap' Alpine component in app.js.
