/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */





import VueLazyload from "vue-lazyload";

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import store from '../store'
import Vue from 'vue'
import { BootstrapVue, IconsPlugin,VBToggle  } from 'bootstrap-vue'
import router from "./router.js";
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.component('admin-header', require('./components/AdminHeader').default);
Vue.component('index', require('./views/App').default);
Vue.component('left-menu', require('./components/LeftMenu').default);
Vue.component('mode-toggler', require('./components/toggle/ModeToggler').default);
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import vl from 'vue-lazyload'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(vl)
Vue.directive('b-toggle', VBToggle)
Vue.prototype.$role = $cookies.get("ROLE")

const app = new Vue({
    el: '#app',
    router,
    store,
    mounted(){
        let url = document.location.href
        let publicError = url.indexOf("/public/")
        if(publicError !== -1){
            document.location.href = url.replace("/public/","/")
        }
    }
});
