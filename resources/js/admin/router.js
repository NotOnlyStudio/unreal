import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)
import Index from "./views/index";
import Categories from "./views/Categories";
import CategoryEdit from "./views/CategoryEdit";
import Products from "./views/Products";
import User from "./views/User";
import UserEdit from "./views/UserEdit";
import Comments from "./views/Comments";
import Forum from "./views/Forum";
const Blog = () => import("./views/Blog")
const Topics = () => import("./views/Topics")
const Gallery = () => import("./views/Gallery")
const ChallengeMembers = () => import("./views/ChallegeMembers");
const Challenges = () => import("./views/Challenges")
import AdminFaq from "./views/AdminFaq"
import Reports from "./views/Reports"
import Polytics from "./views/Polytics"
import PolyticsEdit from "./views/PolyticsEdit"
import Settings from "./views/Settings"
import Logs from "./views/Logs"
const routes = [
    {
        path: "/admin",
        component: Index,
        name: 'Dashboard',
    },
    {
        path: "/admin/categories",
        component: Categories,
        name: 'Categories',
    },
    {
        path: "/admin/categories/edit/:id",
        component: CategoryEdit,
        name: 'Edit category',
    },
    {
        path:"/admin/models",
        component: Products,
        name:"Models",
    },

    {
        path:"/admin/users",
        component: User,
        name: "Users",
    },
    {
        path:"/admin/user/:id",
        component: UserEdit,
        name: "Edit user",
    },
    {
        path:"/admin/polytics",
        component: Polytics,
        name: "Polytics",
    },
    {
        path:"/admin/polytics/:alias",
        component: PolyticsEdit,
        name: "Polytics edit",
    },
    {
        path:"/admin/comments",
        component: Comments,
        name: "Comments",
    },
    {
        path:"/admin/forum",
        component: Forum,
        name: "Forum",
    },
    {
        path:"/admin/challenges",
        component: Challenges,
        name:"Challenges"
    },
    {
        path:"/admin/challenges/:title/members",
        component: ChallengeMembers,
        name:"Challenge members"
    },
    {
        path:"/admin/blog",
        component: Blog,
        name:"Blog"
    },
    {
        path:"/admin/topics",
        component: Topics,
        name:"Topics"
    }
    ,
    {
        path:"/admin/gallery",
        component: Gallery,
        name:"Gallery"
    },
    {
        path:"/admin/faq",
        component: AdminFaq,
        name:"Faq"
    },
    {
        path:"/admin/reports",
        component: Reports,
        name:"Reports"
    },
    {
        path:"/admin/settings",
        component: Settings,
        name:"Settings"
    },
    {
        path:"/admin/logs",
        component: Logs,
        name:"Logs"
    }
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})
router.beforeEach((to, from, next) => {
    document.title = to.name
    next()
});

export default router
