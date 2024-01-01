import Vue from 'vue'
import VueRouter from 'vue-router'
import cookie from 'vue-cookies'

const user = cookie.get("user");
const middleware = (to,from, next) => {
    if(user == null){
        document.location.href="/login"
    }
}

Vue.use(VueRouter)
// const Index = () =>import("./views/index")
const Blog = () => import("./views/Blog")
const Login = () =>import("./views/Login")
const Register = () =>  import("./views/Register")
const uploadArt = () =>import("./views/uploadArt")
const Gallery = () => import("./views/Gallery")
const GalleryItem = () => import("./views/GalleryItem")
const Logout = () =>  import("./views/logout")
const UploadModel = () =>  import("./views/uploadModel")
const ModelItem = () =>  import("./views/ModelItem")
const ArticleAdd = () =>  import("./views/Article-add")
// const Forum = () =>  import("./views/Forum")
const Store = () =>  import('./views/Store')
const Topics = () => import("./views/Topics")
const CreateTopic = () => import("./components/CreateTopic")
const TopicPage = () => import("./views/TopicPage")
// const Challenge = () => import("./views/Challenge")
const BlogPage = () => import("./views/BlogPage");
// const ChallengeMain = () => import("./views/ChallengeMain");
const AvardsPage = () => import("./views/AvardsPage");
const Faq = () => import("./views/FAQ")
const ForumSearchPage = () => import("./views/ForumSearch")
const routes = [
    // {
    //     path: '/register',
    //     component: Register,
    //     name: 'Register',
    // },
    // {
    //     path: '/forum/search/:title',
    //     component: ForumSearchPage,
    //     name: 'Search on forum',
    // },
    // {
    //     path: "/",
    //     component: Index,
    //     name: 'UnrealShop',
    // },
    // {
    //     path: "/forum",
    //     component: Forum,
    //     name: 'Forum',
    // },
    // {
    //     path: '/blog',
    //     component: Blog,
    //     name: 'Blog',
    // },
    // {
    //     path: '/blog/:item',
    //     component: BlogPage,
    //     name: 'Blog article',

    // },
    // {
    //     path: '/login',
    //     component: Login,
    //     name: 'Login',
    // },
    // {
    //     path: '/logout',
    //     component: Logout,
    //     name:"Logout",
    // },
    // {
    //     path: '/gallery',
    //     component: Gallery,
    //     name:"Gallery",
    // },
    // {
    //     path: '/store',
    //     component: Store,
    //     name:"Store",
    // },
    // {
    //     path: '/upload-art',
    //     component: uploadArt,
    //     name: 'Upload Art',

    // },
    // {
    //   path:"/upload-model",
    //   component: UploadModel,
    //   name:"Upload model",

    // },
    // {
    //     path:"/upload-article",
    //     component: ArticleAdd,
    //     name:"Upload  article",

    // },
    // {
    //     path: '/gallery/:alias',
    //     component: GalleryItem,
    //     name:"Gallery article",
    // },
    // {
    //     path: '/gallery/avards/:alias',
    //     component: AvardsPage,
    //     name:"Avard",
    //     props:(route) =>{
    //         avards: true
    //     }
    // },
    // {
    //     path:"/models/:alias",
    //     component: ModelItem,
    //     name:"Model",
    // },
    // {
    //     path:"/forum/:alias",
    //     component: Topics,
    //     name:"Topics",
    // },
    // {
    //     path:"/forum/:alias/create-topic",
    //     component: CreateTopic,
    //     name:"Topic upload",
    // },
    // {
    //     path:"/challenge/:alias",
    //     component:Challenge,
    //     name:"Challenge",
    // },
    // {
    //     path:"/challenge/:alias/cards",
    //     component:ChallengeMain,
    //     name:"Results",
    // },
    // {
    //     path:"/forum/:alias/:contentAlias",
    //     component: TopicPage,
    //     name:"Forum article",
    // },
    // {
    //     path:"/faq",
    //     component:Faq,
    //     name:"FAQ",
    // }
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})
// router.beforeEach((to, from, next) => {

//     document.title = to.name
//     next()
// });

export default router
