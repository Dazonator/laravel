<template>
    <main class="col py-4">
        <div class="row">
            <div class="col task-page"  v-if="loaded">
                <h1>{{task.title}}</h1>
                <!--            <pre v-html="tasks"></pre>-->
                <div class="task-page__deadline" v-if="task.deadline">
                    Дедлайн  {{ task.deadline | deadLine }}
                </div>


                <div class="task-page__status">
                    <span>{{task.status.status}}</span>
                </div>

                <div class="task-page__priority" v-if="task.priority">
                    <span>{{task.priority.priority}}</span>
                </div>

                <p class="task-page__text" v-if="task.text">
                    {{task.text}}
                </p>

                <div class="task-page__created">
                    Создано {{ task.created_at | createDate }}
                </div>

                <div class="responsibles">
                    <h6 class="responsibles__title">Исполнителей в этой задаче: {{task.responsibles.length}}</h6>
                    <ul class="responsibles__items">
                        <li
                            v-for="item in task.responsibles"
                            :key="item.id"
                        >
                            <router-link
                                :to="'/profile/' + item.id"
                            >
                                <v-avatar
                                    left
                                    size="28"
                                >
                                    <v-img
                                        :src=item.img
                                        alt=item.name
                                    ></v-img>
                                </v-avatar>
                            </router-link>
                        </li>
                    </ul>
                </div>

                <div class="mb-6">

                    <v-btn
                        color="error"
                        @click="taskDelete()"
                    >
                        Удалить задачу
                    </v-btn>

                    <v-btn
                        v-if="!task.distribution_department"
                        color="success"
                        @click.stop="taskCompleted()"
                        :disabled="!statusActive"
                    >
                        {{(!statusActive) ? 'Задача завершена' : 'Завершить задачу'}}
                    </v-btn>

                    <v-btn
                        color="primary"
                        @click="restoreTask()"
                        v-if="!statusActive"
                    >
                        Восстановить задачу
                    </v-btn>

                    <v-btn
                        v-if="statusActive"
                        color="yellow darken-3"
                        dark
                        @click="edit()"
                    >
                        {{task.distribution_department ? 'Распределить' : 'Редактировать'}}
                    </v-btn>

                    <v-btn
                        v-if="statusActive && !task.distribution_department"
                        color="primary"
                        @click="subtask()"
                    >
                        Создать подзадачу
                    </v-btn>



                    <v-dialog
                        v-model="dialogCannotBeCompleted"
                        max-width="400px"
                    >
                        <v-card>
                            <h3 class="pa-4">
                                Задача не может быть завершена. Есть незакрытые подзадачи
                            </h3>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="green darken-1"
                                    text
                                    @click="dialogCannotBeCompleted = false"
                                >
                                    Понятно
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog
                        v-model="dialogCannotBeRestore"
                        max-width="400px"
                    >
                        <v-card>
                            <h3 class="pa-4">
                                Задача не может быть восстановлена. Родительская задача завершена
                            </h3>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    color="green darken-1"
                                    text
                                    @click="dialogCannotBeRestore = false"
                                >
                                    Понятно
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </div>

                <div class="subtasks mb-12" v-if="task.children.length!==0">
                    <h5 class="subtasks__title">Подзадачи</h5>
                    <div class="subtasks-table">
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                <tr
                                    v-for="item in task.children"
                                >
                                    <td>
                                        <router-link
                                            :to="'/tasks/task/' + item.id"
                                        >
                                            {{item.title}}
                                        </router-link>
                                    </td>
                                    <td>
                                        {{ item.text }}
                                    </td>

                                    <td>
                                        {{ item.status_id }}
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </div>
                </div>

                <div class="parent-tasks mb-12" v-if="task.parent">
                    <h5 class="parent-tasks__title">Родительская задача</h5>
                    <div class="parent-tasks-table">
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                    <tr>
                                        <td>
                                            <router-link
                                                :to="'/tasks/task/' + task.parent.id"
                                            >
                                                {{task.parent.title}}
                                            </router-link>
                                        </td>
                                        <td>
                                            {{ task.parent.text }}
                                        </td>
                                        <td>
                                            {{ task.parent.status_id }}
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </div>
                </div>

                <div class="task-messages bg">
                    <div class="fill-height  pa-6">
                        <v-row
                            class="fill-height container-chat"
                            align="end"
                        >
                            <v-col>
                                <div
                                    v-for="(item, index) in chat"
                                    :key="index"
                                    :class="['d-flex flex-row align-center my-2', item.user.id == $store.state.user.authUser.id ? 'justify-end': null]"
                                >
                                    <div
                                        class="container-chat__message d-flex flex-row align-center"
                                        :class="[[item.user.id == $store.state.user.authUser.id ? 'user-message' : '']]"
                                    >
                                        <v-avatar
                                            left
                                            size="32"
                                            class="container-chat__avatar"
                                        >
                                            <v-img
                                                :src=item.user.img
                                                alt=item.name
                                            ></v-img>
                                        </v-avatar>
                                        <div :class="[[item.user.id == $store.state.user.authUser.id ? 'mr-3 text-end' : 'ml-3']]">
                                            <div
                                                class="container-chat__name font-weight-bold mb-1"
                                                :class="[[item.user.id == $store.state.user.authUser.id ? 'ml-1' : 'mr-1']]"
                                            >
                                                {{item.user.name}}
                                            </div>
                                            <div class="mb-1">
                                                {{ item.message }}
                                            </div>
                                            <div
                                                class="container-chat__time text--gray"
                                            >
                                                {{ item.created_at | messageTime }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </div>

                    <v-divider class=""></v-divider>
                    <div class="d-flex flex-row align-center px-6">
                        <v-text-field
                            v-model="message"
                            dense
                            placeholder="Текст сообщения..."
                            @keypress.enter="sendMessage"
                            class="ma-0"
                            height="55"
                        >

                        </v-text-field>
                        <v-btn icon class="ml-4" @click="sendMessage">
                            <v-icon>mdi-send</v-icon>
                        </v-btn>
                    </div>
                </div>
            </div>

            <add-task
                v-show="showform"
                :isEdit="isEdit"
                :isSubtask="isSubtask"
                :parent_id="id"
                :foredit="foredit"
            ></add-task>

        </div>
    </main>
</template>

<script>
import moment from 'moment';
// import { mapGetters, mapActions } from "vuex";
export default {
    name: 'task',
    props: {
        taskId: Number,
    },
    data() {
        return {
            chat: {},
            message: '',
            statusActive: true,
            errors: {},
            dialogCannotBeCompleted: false,
            dialogCannotBeRestore: false,
            isEdit: false,
            isSubtask: false,
            foredit:[],
            loaded: false,
            showform: false,
            id: null,
            task: [],
        }
    },
    created(){
        moment.locale('ru');
        this.init();
    },
    watch:{
        $route (to, from){
            this.id = Number(this.$route.params.id)
            this.init();
        }
    },
    methods: {
        init() {
            if(Number(this.$route.params.id)){
                this.id = Number(this.$route.params.id);
            }
            if(this.taskId){
                this.id = this.taskId;
            }
            axios.get(`/api/tasks/task/${this.id}`).then(response => {
                console.log(response.data);
                this.task = response.data;
                this.loaded = true;
                if(this.task.status_id === 3){
                    this.statusActive = false;
                } else {
                    this.statusActive = true;
                }
            });

            this.initMessages();
        },
        edit(){
            this.isEdit = true;
            this.isSubtask = false;
            this.foredit = this.task;
            this.showform = true;
        },
        subtask(){
            this.isEdit = false;
            this.isSubtask = true;
            this.foredit = [];
            this.showform = true;
        },
        taskCompleted(){
            if(this.task.children.length > 0){
                for(let child of this.task.children){
                    if(child.status_id !== 3){
                        this.dialogCannotBeCompleted = true;
                        return;
                    }
                }
            }

            this.errors = {};
            axios.post(`/api/tasks/completed/${this.id}`, this.task.id).then(response => {
                // alert('Задача завершена!!!');
                // window.location.href = `/tasks/${this.task.id}`;
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        restoreTask(){
            if(this.task.parent){
                if(this.task.parent.status_id === 3){
                    this.dialogCannotBeRestore = true;
                    return;
                }
            }
            axios.post(`/api/tasks/restore/${this.id}`, this.task.id).then(response => {
                // alert('Задача восстановлена!!!');
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        taskDelete(){
            axios.post(`/api/tasks/delete/${this.task.id}`).then(response => {
                alert('Задача завершена!!!');
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        initMessages() {
            axios.post(`/api/tasks/task/messages/${this.id}`).then(response => {
                // console.log(response.data);
                this.chat = response.data;
                this.scrollToEnd();

                this.$store.dispatch('user/getAppParameters');

                this.$emit('updateNotifications', true);
            });
        },
        sendMessage() {
            let newMessage = {
                message: this.message,
                task_id: this.id
            }
            axios.post(`/api/tasks/task/messages/send`, newMessage).then(response => {
                this.initMessages();
                setTimeout( () => {
                    this.scrollToEnd()
                }, 100);
                this.message = "";
            });
        },
        scrollToEnd() {
            setTimeout( () => {
                var objDiv = this.$el.querySelector(".container-chat");
                objDiv.scrollTop = objDiv.scrollHeight;
            }, 100);
        },
    },
    filters: {
        deadLine: function (date) {
            return moment(date).format('LL');
        },
        createDate: function (date) {
            return moment(date).format('l');
        },
        messageTime: function (date) {
            return moment(date).fromNow();
        }
    }
}
</script>

<style>
    .task-messages{
        display: flex;
        flex-direction: column;
    }
    .task-messages .v-footer{
        flex-grow: 1;
    }

    .task-messages .row{
        height:500px;
        /*scroll-behavior: smooth;*/
        overflow: auto;
        transition: none;
    }

    .container-chat{
        line-height: 1.2;
    }
    .container-chat .container-chat__message{
        padding: 5px 10px;
        border-radius: 10px 10px 10px 0;
        background-color: #f5f5f5;
        position: relative;
    }
    .container-chat .container-chat__message.user-message{
        background-color: #F2F2FF;
    }
    .container-chat .container-chat__message.user-message{
        border-radius: 10px 10px 0 10px;
    }

    .container-chat .container-chat__message::before,
    .container-chat .container-chat__message::after{
        content: '';
        display: block;
        width: 10px;
        height: 20px;
        position: absolute;
    }

    .container-chat .container-chat__message.user-message::before{
        left: 100%;
    }.container-chat .container-chat__message::before{
        background-color: #eee;
        left: auto;
        right: 100%;
        bottom: 0;
     }
    .container-chat .container-chat__message.user-message::after{
        left: 100%;
        border-radius: 0 0 0 7px;
    }
    .container-chat .container-chat__message::after{
        background-color: #fff;
        left: auto;
        right: 100%;
        bottom: 0;
        border-radius: 0 0 7px 0;
    }



    .container-chat .container-chat__name{
        font-size: 12px;
    }
    .container-chat .container-chat__time{
        font-size: 10px;
    }
    .container-chat__message.user-message .container-chat__avatar{
        order: 1;
    }




    .container-chat::-webkit-scrollbar {
        width: 3px;
        background-color: #f9f9fd;
    }

    .container-chat::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: rgb(25, 118, 210)
    }

    .container-chat::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.2);
        border-radius: 10px;
        background-color: #f9f9fd;
    }
</style>
