export default {
    namespaced: true,
    state: {
        authUser: {},
        notifications: null,
        permissions: {},
        calendarIndex: 0,

    },
    mutations: {
        setAuthUser(state, authUser){
            state.authUser = authUser;
        },
        setNotifications(state, notifications){
            state.notifications = notifications;
        },
        setPermissions(state, permissions){
            state.permissions = permissions;
        },
        setCalendarIndex(state){
            state.calendarIndex +=1;
        }
    },
    getters: {
        authUser(state) {
            return state.authUser;
        },
        notifications(state) {
            return state.notifications;
        },
        permissions(state) {
            return state.permissions;
        },
        calendarIndex(state) {
            return state.calendarIndex;
        }
    },
    actions: {
        getAppParameters(context){
             return axios.post(`/api/main-app-parameters`).then(response => {
                context.commit('setAuthUser', response.data.authUser);
                context.commit('setNotifications', response.data.notifications);
                context.commit('setPermissions', response.data.permissions);
            });
        },
        plusCalendarIndex(context){
            context.commit('setCalendarIndex');
        }
    }
}
