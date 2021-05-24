require('./bootstrap');

// Require Vue
window.Vue = require('vue').default;

// Register Vue Components
Vue.component('spam-check-area', require('./components/SPAMCheckArea.vue').default);


if (messages) {
    Vue.prototype.$messages = messages;
}

// Initialize Vue
const app = new Vue({
    el: '#app',
});