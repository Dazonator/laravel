import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import calendar from './views/calendar';
import profile from './views/profile';
import messages from "./views/messages";
import structure from "./views/structure";
import tasks from "./views/tasks";
import task from "./views/tasks/task";
import team from "./views/team";
import home from "./views/home";

const routes = [
    {
        path: "/",
        name: 'home',
        component: home
    },
    {
        path: "/calendar",
        name: 'calendar',
        component: calendar
    },
    {
        path: "/messages",
        name: 'messages',
        component: messages
    },
    {
        path: "/profile",
        name: 'profile',
        component: profile
    },
    {
        path: "/structure",
        name: 'structure',
        component: structure
    },
    {
        path: "/tasks",
        name: 'tasks',
        component: tasks
    },
    {
        path: "/tasks/:id",
        name: 'task',
        component: task,
        props: true
    },
    {
        path: "/team",
        name: 'team',
        component: team
    }
];

export default new VueRouter({
   mode: "history",
   routes
});
