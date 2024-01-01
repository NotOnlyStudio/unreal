require('../bootstrap');
require('../echo')

window.Vue = require('vue').default;

import Vue from 'vue'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import store from '../store'
import VueLazyload from 'vue-lazyload'
import VueCookies from 'vue-cookies'
import VScrollLock from 'v-scroll-lock'
import vClickOutside from 'v-click-outside'
import './../store/i18n'

Vue.filter(
    'webpAdd',(data)=>{
        let text = data.split(".")
        text.pop();
        return text+".webp"
    }
)
Vue.filter(
    'prefixer',(data)=>{
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
        return text+"_thumb.webp 1x"
    }
)
Vue.filter(
    'thumb',(data)=>{
        let text = data.split(".")
        let prefix = text.pop();
        let url = text.join(".")
        return url+"_thumb."+prefix+ " 1x"
    }
)

Vue.component('banner', require('../components/Banner.vue').default);
Vue.component('vheader', require('../components/Header.vue').default);
Vue.component('profile-header', require('./Components/ProfileHeader.vue').default);
Vue.component('cabinet-header', require('./Components/CabinetHeader.vue').default);
Vue.component('cabinet-header-user', require('./Components/CabinetHeaderUser.vue').default);
Vue.component('messanger', require('./Components/Messanger/MessangerWrapper.vue').default);
Vue.component('notifications', require('./Views/Notifications.vue').default);
Vue.component('forum', require('./Views/Forum.vue').default);
Vue.component('blog', require('./Views/Blog.vue').default);
Vue.component('gallery', require('./Views/Gallery.vue').default);
Vue.component('models-page', require('./Views/Models.vue').default);
Vue.component('bookmarks-forum', require('./Views/Bookmarks/BookmarksForum.vue').default);
Vue.component('bookmarks-gallery', require('./Views/Bookmarks/BookmarksGallery.vue').default);
Vue.component('bookmarks-models', require('./Views/Bookmarks/BookmarksModels.vue').default);
Vue.component('bookmarks-blog', require('./Views/Bookmarks/BookmarksBlog.vue').default);
Vue.component('bookmarks-nav', require('./Components/BookmarkNav.vue').default);
Vue.component('bookmark-element', require('../components/Bookmark.vue').default);
Vue.component('purchases-page', require('./Views/PurchasesPage.vue').default);
Vue.component('charts', require('./Components/ChartItem.vue').default);
Vue.component('purchases-block', require('./Components/PurchasesBlock.vue').default);
Vue.component("challenge-banner",require("../components/Challenges/ChallengesWrapper").default);
Vue.component("report-form",() => import("../views/reports/ReportWrapper"));
Vue.component("transfer-block",() => import("./Components/WalletTransfer"));
//--------------------
Vue.use(VueLazyload)
Vue.use(VueCookies)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VScrollLock)
Vue.use(vClickOutside)

Vue.prototype.$imgRoute = "/storage/app/public/";

const app = new Vue({
    el: '#app',
    store,
    data(){
        return{
            reportWrapper: false,
            preloader: true,
        }
    },
    methods:{
        reportsMeth(){
            console.clear()
        }
    },
    mounted(){
        let url = document.location.href
        let publicError = url.indexOf("/public/")
        if(publicError !== -1){
            document.location.href = url.replace("/public/","/")
        }
    }
});
