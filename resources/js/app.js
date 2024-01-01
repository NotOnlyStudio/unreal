require('./bootstrap');
window.Vue = require('vue').default;


import Vue from 'vue'
import { BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import store from './store'
import VueCookies from 'vue-cookies'
import vClickOutside from 'v-click-outside'
import VScrollLock from 'v-scroll-lock'
import './store/i18n'


Vue.component('v-footer', () => import("./components/Footer.vue"))
Vue.component('banner', ()=>import('./components/Banner.vue'));
Vue.component('vheader', ()=>import('./components/Header.vue'));
Vue.component('app', ()=>import('./App.vue'));
Vue.component('app-index', ()=>import('./views/index.vue'));
Vue.component('cookies-modal', ()=>import('./components/CookieModal.vue'));
Vue.component('right-menu', ()=>import('./components/RightMenu/RightMenuWrapper.vue'));
Vue.component('model', ()=>import('./views/ModelItem.vue'));
Vue.component('basket-form', ()=>import('./views/ModelItem.vue'));
Vue.component('forum', ()=>import('./views/Forum.vue'));
Vue.component('topics', ()=>import('./views/Topics.vue'));
Vue.component('login-form', () => import('./components/LoginForm.vue') );
Vue.component('basket-wrapper', () => import('./components/Basket/BasketWrapper.vue'));
Vue.component('linker-basket-wrapper', () => import('./components/Basket/LinkerBasketWrapper.vue'));
Vue.component('blog', () => import('./views/Blog.vue'));
Vue.component('gallery', () => import('./views/Gallery.vue'));
Vue.component('store', () => import('./views/Store.vue'));
Vue.component("register-form", () => import('./views/Register.vue'));
Vue.component("upload-model", () => import("./views/uploadModel"));
Vue.component("upload-gallery",() => import("./views/uploadArt"));
Vue.component("upload-blog",() => import("./views/Article-add"));
Vue.component("topic-page",() => import("./views/TopicPage"));
Vue.component("topic-create",() => import("./components/CreateTopic"));
Vue.component("blog-page",() => import("./views/BlogPage"));
Vue.component("gallery-item",() => import("./views/GalleryItem"));
Vue.component("avard-page",() => import("./views/AvardsPage"));
Vue.component("faq-container",() => import("./views/FaqContainer"));
Vue.component("report-form",() => import("./views/reports/ReportWrapper"));
Vue.component("challenge-page",() => import("./views/Challenge"));
Vue.component("challenge-cards",() => import("./views/ChallengeMain"));
Vue.component("challenge-banner",() => import("./components/Challenges/ChallengesWrapper"));
Vue.component("preloader",() => import("./components/preloader"));
Vue.component("forum-search",() => import("./views/ForumSearch"));
Vue.component("reset-password-email",() => import("./views/ResetPassword-Email"));
Vue.component("reset-password",() => import("./views/PasswordReset"));
Vue.component("edit-article",() => import("./views/edit/article"));

//filters
Vue.filter(
    'webpAdd',(data)=>{
        let text = data.split(".")
        text.pop();
        return text+".webp"
    }
)
Vue.filter(
    'prefix',(data)=>{
        let text = data.split(".")
        let prefix = text.pop();
        let url = text.join(".")
        return url+"."+prefix
    }
)

Vue.filter(
    'webpThumb',(data)=>{
        let text = data.split(".")
        text.pop();
        return text+"_thumb.webp"
    }
)

Vue.filter(
    'thumb',(data)=>{
        let text = data.split(".")
        let prefix = text.pop();
        let url = text.join(".")
        return url+"_thumb."+prefix
    }
)



Vue.use(VueCookies)
Vue.use(BootstrapVue)
Vue.use(VScrollLock)
Vue.use(vClickOutside)
Vue.use(IconsPlugin)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$imgRoute = "/storage/app/public/";//
Vue.prototype.$role = $cookies.get("ROLE")
Vue.prototype.$challengeProps = "";


const app = new Vue({
    el: '#app',
    store: store,
    data(){
        return{
            reportWrapper: false,
            preloader: true,
        }
    },
    methods:{
        pizdec() {
            console.log('test');
        }
    },
    mounted(){
        this.preloader = false
        if(document.location.pathname != "/"){
            this.$store.dispatch("setChallengeStyles",`top:70px;`)
        }
        let url = document.location.href
        let publicError = url.indexOf("/public/")
        if(publicError !== -1){
            document.location.href = url.replace("/public/","/")
        }

        this.pizdec();

        // console.log('test');
    }

});
