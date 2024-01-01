import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const Messages = () => import("./Views/Messages");
const Forum = () => import("./Views/Forum");
const Blog = () => import("./Views/Blog");
const Gallery = () => import("./Views/Gallery");
const Bookmarks= () => import("./Views/Bookmarks");
const Models = () => import("./Views/Models");
const Purchase = () => import("./Views/Purchase");
const Profit = () => import("./Views/Profit");
const Notifications =  () => import("./Views/Notifications")
const BookmarksForum = () => import("./Views/Bookmarks/BookmarksForum");
const BookmarksGallery = () => import("./Views/Bookmarks/BookmarksGallery");
const BookmarksBlog = () => import("./Views/Bookmarks/BookmarksBlog");
const BookmarksModels = () => import("./Views/Bookmarks/BookmarksModels");

const routes = [
    {
        name:"Notifications",
        component: Notifications,
        path:"/cabinet/notifications"
    },

    {
        name:"Messages",
        component: Messages,
        path:"/cabinet/messages"
    },
    {
        name:"Forum",
        component:Forum,
        path:"/cabinet/forum"
    },
    // {
    //     name:"Blog",
    //     component:Blog,
    //     path:"/cabinet/blog"
    // },
    // {
    //     name:"Gallery",
    //     component:Gallery,
    //     path:"/cabinet/gallery",

    // },

    {
        name:"Bookmarks",
        component:Bookmarks,
        path:"/cabinet/bookmarks",
        redirect:"/cabinet/bookmarks/forum",
        children:[
            {
                name:"Forum",
                component:BookmarksForum,
                path:"forum"
            },
            {
                name:"Blog",
                component:BookmarksBlog,
                path:"blog"
            },
            {
                name:"Gallery",
                component:BookmarksGallery,
                path:"gallery"
            },
            {
                name:"Models",
                component:BookmarksModels,
                path:"models"
            },
        ],
    },
    // {
    //     name:"Models",
    //     component:Models,
    //     path:"/cabinet/models"
    // },
    {
        name:"Purchase",
        component:Purchase,
        path:"/cabinet/purchase"
    },
    {
        name:"Profit",
        component:Profit,
        path:"/cabinet/profit"
    }

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
