/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//Vue.prototype.$eventBus = new Vue();



window.Vue = require('vue').default;
import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue' //Importing
Vue.use(BootstrapVue) // Telling Vue to use this in whole application

//window.EventBus = new Vue();
//Vue.prototype.$eventBus = new Vue();
Vue.prototype.$eventBus = new Vue(); // 1способ
//const eventBus = new Vue(); // 2 способ​

//import BootstrapVueIcons from 'bootstrap-vue'
//Vue.use(BootstrapVueIcons)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('chat', require('./components/Chat.vue').default);
Vue.component('v-header', require('./components/Header.vue').default);

Vue.component('smallwin', require('./views/smallWin.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



import router from "./router"

const app = new Vue({
    el: '#app',
    router
});
