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
        component: home,
        meta: {title: 'Домашняя страница'}
    },
    {
        path: "/calendar",
        name: 'calendar',
        component: calendar,
        meta: {title: 'Календарь'}
    },
    {
        path: "/meetings",
        name: 'meetings',
        component: meetings,
        meta: {title: 'Собрания'}
    },
    {
        path: "/meetings/:meetingId",
        name: 'meeting',
        component: meeting,
        props: true,
        meta: {title: 'Собрание'}
    },
    {
        path: "/messages",
        name: 'messages',
        component: messages,
        props: true,
        meta: {title: 'Сообщения'}
    },
    {
        path: "/messages/:taskId",
        name: 'messages-task',
        component: messages,
        props: true,
        meta: {title: 'Сообщения'}
    },
    {
        path: "/profile",
        name: 'profile',
        component: profile,
        meta: {title: 'Профиль'}
    },
    {
        path: "/profile/:id",
        name: 'profile_id',
        component: profile,
        props: true,
        meta: {title: 'Профиль'}
    },
    {
        path: "/tasks",
        name: 'tasks',
        component: tasks,
        meta: {title: 'Задания'}
    },
    {
        path: "/tasks/status/:statusId",
        name: 'tasks_id',
        component: tasks,
        props: true,
        meta: {title: 'Задания'}
    },
    {
        path: "/tasks/department/:departmentId",
        name: 'department_id',
        component: tasks,
        props: true,
        meta: {title: 'Задания'}
    },
    {
        path: "/tasks/task/:id",
        name: 'task',
        component: task,
        props: true,
        meta: {title: 'Задания'}
    },
    {
        path: "/team",
        name: 'team',
        component: team,
        meta: {title: 'Команда'}
    },

    {
        path: "/team/:department",
        name: 'team-department',
        component: team,
        props: true,
        meta: {title: 'Команда'}
    },
    {
        path: "/team/users/:userId",
        name: 'team-user',
        component: team,
        props: true,
        meta: {title: 'Команда'}
    },
    {
        path: "/structure",
        name: 'structure',
        component: structure,
        meta: {title: 'Структура'}
    },
    {
        path: "/structure/:categoryId",
        name: 'structure-id',
        component: structure,
        meta: {title: 'Структура'}
    },
    {
        path: "/settings/",
        name: 'settings',
        component: settings,
        meta: {title: 'Настройки'}
    },
    {
        path: "/settings/users",
        name: 'settings-users',
        component: settingsUsers,
        meta: {title: 'Пользователи'}
    },
    {
        path: "/settings/roles",
        name: 'settings-roles',
        component: settingsRoles,
        meta: {title: 'Роли'}
    },
    {
        path: "/settings/roles/create-role",
        name: 'create-role',
        component: createRole,
        meta: {title: 'Роли'}
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
