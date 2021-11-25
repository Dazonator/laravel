import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import calendar from './views/calendar';
import meetings from './views/meetings';
import meeting from './views/meetings/meeting';
import profile from './views/profile';
import messages from "./views/messages";
import structure from "./views/structure";
import tasks from "./views/tasks";
import task from "./views/tasks/task";
import team from "./views/team";
import home from "./views/home";
import settings from "./views/settings";

import settingsUsers from "./views/settings/settings-users";
import settingsRoles from "./views/settings/settings-roles";
import createRole from "./views/settings/roles/create-role";


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
        path: "/meetings",
        name: 'meetings',
        component: meetings
    },
    {
        path: "/meetings/:meetingId",
        name: 'meeting',
        component: meeting,
        props: true
    },
    {
        path: "/messages",
        name: 'messages',
        component: messages,
        props: true
    },
    {
        path: "/messages/:taskId",
        name: 'messages-task',
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
    },
    {
        path: "/structure",
        name: 'structure',
        component: structure
    },
    {
        path: "/structure/:categoryId",
        name: 'structure-id',
        component: structure
    },
    {
        path: "/settings/",
        name: 'settings',
        component: settings
    },
    {
        path: "/settings/users",
        name: 'settings-users',
        component: settingsUsers
    },
    {
        path: "/settings/roles",
        name: 'settings-roles',
        component: settingsRoles
    },
    {
        path: "/settings/roles/create-role",
        name: 'create-role',
        component: createRole
    },
    {
        path: "/settings/roles/update-role/:roleId",
        name: 'update-role',
        component: createRole,
        props: true
    },
];

export default new VueRouter({
   mode: "history",
   routes
});
