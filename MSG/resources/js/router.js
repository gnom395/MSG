import vueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(vueRouter);

import Index from "./views/Index";
import Changename from "./views/Changename";


const routes = [
    {
        path: "/",
        component: Index
    },
    {
        path: "/changename",
        component: Changename
    }

];

export default new vueRouter({
    mode: "history",
    routes
});
