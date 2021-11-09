import vueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(vueRouter);

import Index from "./views/Index";
import Changename from "./views/Changename";
import UsersWin from "./components/UsersWin";

const routes = [
    {
        path: "/",
        component: Index
    },
    {
        path: "/changename",
        component: Changename
    },
    {
        path: "/userwin",
        component: UsersWin
    },
    {
        path: "/:ug/:id",
        component: Index,
    },
    {
        path: "/user/:id/:idmes",
        component: Index
    },
    {
        path: "/chat",
        component: Index
    }

];

export default new vueRouter({
    mode: "history",
    routes
});
