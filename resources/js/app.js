require('./bootstrap');

window.Vue = require('vue');

import VueChatScroll from 'vue-chat-scroll'
Vue.use(require('vue-chat-scroll'))


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-component', require('./components/MainComponent.vue').default);
Vue.component('user-list-component', require('./components/UserListComponent.vue').default);
Vue.component('chat-component', require('./components/ChatComponent.vue').default);
Vue.component('video-component', require('./components/VideoComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    created(){
        Echo.channel('updateUserStatus')
        .listen('UpdateUserOnlineStatus',(e) =>{
            console.log(e);
        });
    }
});
