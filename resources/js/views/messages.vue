<template>
    <main class="col py-4" v-if="loaded">
        <div class="row">
            <div class="col messages-nav" >
                <div class="bg py-6 px-10 mb-2">
                    <v-list-item-group
                        color="primary"
                    >
                        <v-list>
                            <h5 class="team-nav__title">Сообщения в задачах</h5>
                            <v-list-item
                                v-for="(message, i) in messages"
                                :key="i"
                                class="align-stretch"
                            >

                                <v-badge
                                    color="red"
                                    :content="message.task_notifications_count"
                                    class="flex-grow-1 align-self-center"
                                    inline
                                >
                                    <router-link
                                        :to="'/messages/' + message.id"
                                        class="flex-grow-1 align-self-center"
                                    >
                                        {{message.title}}
                                    </router-link>
                                </v-badge>

                            </v-list-item>
                        </v-list>
                    </v-list-item-group>
                </div>
            </div>

            <task
                v-if="taskId"
                :taskId="taskId"
                @updateNotifications="init()"
            ></task>

        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            loaded: false,
            messages: {},
            taskId: Number(this.$route.params.taskId),
        }
    },
    watch:{
        '$route.path' (to, from){
            this.taskId = Number(this.$route.params.taskId);
        }
    },
    created(){
        this.init();
    },
    methods: {
        init(){
            axios.get('/api/messages').then(response => {
                console.log(response.data);
                this.messages = response.data;
                this.loaded = true;
            });
        }
    },

}
</script>
