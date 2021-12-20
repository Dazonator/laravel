<template>
    <v-row v-if="loaded">
        <v-col
            cols="12"
            md="3"
        >
            <div class="bg py-4 px-4 mb-2">
                <v-list-item-group
                    color="primary"
                >
                    <v-list>
                        <h5 class="team-nav__title">Сообщения в задачах</h5>
                        <v-list-item
                            v-for="(message, i) in messages"
                            :key="i"
                            class="align-stretch"
                            :to="'/messages/' + message.id"
                        >

                            <v-badge
                                color="red"
                                :content="message.task_notifications_count"
                                class="flex-grow-1 align-self-center"
                                inline
                            >
                                <span
                                    class="flex-grow-1 align-self-center"
                                >
                                    {{message.title}}
                                </span>
                            </v-badge>

                        </v-list-item>
                    </v-list>
                </v-list-item-group>
            </div>
        </v-col>
        <v-col
            cols="12"
            md="9"
        >

            <task
                v-if="taskId"
                :taskId="taskId"
                @updateNotifications="init()"
            ></task>
        </v-col>


    </v-row>
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
            axios.post('/api/messages').then(response => {
                this.messages = response.data;
                this.loaded = true;
            });
        }
    },

}
</script>
