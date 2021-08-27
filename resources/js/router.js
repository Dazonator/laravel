import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import calendar from './views/calendar';
import profile from './views/profile';
import messages from "./views/messages";
import structure from "./views/structure";
import tasks from "./views/tasks";
import team from "./views/team";
import home from "./views/home";

const routes = [
    {
        path: "/calendar",
        component: calendar
    },
    {
        path: "/messages",
        component: messages
    },
    {
        path: "/profile",
        component: profile
    },
    {
        path: "/structure",
        component: structure
    },
    {
        path: "/tasks",
        component: tasks
    },
    {
        path: "/team",
        component: team
    }
];

export default new VueRouter({
   mode: "history",
   routes
});
