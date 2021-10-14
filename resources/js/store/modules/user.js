export default {
    namespaced: true,
    state: {
        user: {},
    },
    mutations: {
        setUser(state, user){
            state.user = user;
        }
    },
    getters: {
        user(state) {
            return state.user;
        }
    },
    actions: {
        getUser(context){
            axios.get(`/api/profile`).then(response => {
                context.commit('setUser', response.data.user);
            });
        },
    }
}
