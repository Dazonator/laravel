export default {
    namespaced: true,
    state: {
        notifications: null,
    },
    mutations: {
        setNotifications(state, notifications){
            state.notifications = notifications;
        }
    },
    getters: {
        notifications(state) {
            return state.notifications;
        }
    },
    actions: {
        getNotifications(context){
            axios.post(`/api/notifications`).then(response => {
                context.commit('setNotifications', response.data);
            });
        }
    }
}
