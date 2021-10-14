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
        component: messages,
        props: true
    },
    {
        path: "/messages/:taskId",
        name: 'messages',
        component: messages,
        props: true
    },
    {
        path: "/profile",
        name: 'profile',
        component: profile,
    },
    {
        path: "/profile/:id",
        name: 'profile_id',
        component: profile,
        props: true
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
        path: "/tasks/status/:statusId",
        name: 'tasks_id',
        component: tasks,
        props: true
    },
    {
        path: "/tasks/department/:departmentId",
        name: 'department_id',
        component: tasks,
        props: true
    },
    {
        path: "/tasks/task/:id",
        name: 'task',
        component: task,
        props: true
    },
    {
        path: "/team",
        name: 'team',
        component: team
    },

    {
        path: "/team/:department",
        name: 'team-department',
        component: team,
        props: true
    },
    {
        path: "/team/users/:userId",
        name: 'team-user',
        component: team,
        props: true
    }
];

export default new VueRouter({
   mode: "history",
   routes
});
