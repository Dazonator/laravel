export default {
    namespaced: true,
    state: {
        authUser: {},
        // statuses: {},
        // departments: {},
        // priorities: {},
        // users: {},
        notifications: null,
    },
    mutations: {
        setAuthUser(state, authUser){
            state.authUser = authUser;
        },
        // setStatuses(state, statuses){
        //     state.statuses = statuses;
        // },
        // setDepartments(state, departments){
        //     state.departments = departments;
        // },
        // setPriorities(state, priorities){
        //     state.priorities = priorities;
        // },
        // setUsers(state, users){
        //     state.users = users;
        // },
        setNotifications(state, notifications){
            state.notifications = notifications;
        }
    },
    getters: {
        authUser(state) {
            return state.authUser;
        },
        // statuses(state) {
        //     return state.statuses;
        // },
        // departments(state) {
        //     return state.departments;
        // },
        // priorities(state) {
        //     return state.priorities;
        // },
        // users(state) {
        //     return state.users;
        // },
        notifications(state) {
            return state.notifications;
        }
    },
    actions: {
        getAppParameters(context){
            axios.post(`/api/main-app-parameters`).then(response => {
                console.log(response.data);
                context.commit('setAuthUser', response.data.authUser);
                // context.commit('setStatuses', response.data.statuses);
                // context.commit('setDepartments', response.data.departments);
                // context.commit('setPriorities', response.data.priorities);
                // context.commit('setUsers', response.data.users);
                context.commit('setNotifications', response.data.notifications);
            });
        },
    }
}
