<template>
    <div>
        <h1>{{title}}</h1>
        <div class="bg pa-4">
            <v-data-table
                :headers="headers"
                :items="tasks"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                :search="search"
                :loading="tableloading"
                :items-per-page="30"
                loading-text="Загрузка задач..."
            >
                <template v-slot:top>
                    <v-toolbar
                        flat
                        class="align-items-center"
                    >
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Поиск..."
                            single-line
                            hide-details
                        ></v-text-field>
                    </v-toolbar>
                </template>
                <template #item.title="{ item }">
                    <router-link

                        :to="'/tasks/task/' + item.id"
                    >
                        {{ item.title }}
                    </router-link>
                </template>

                <template #item.responsibles="{ item }">
                    <router-link
                        v-for="i in item.responsibles"
                        :key="i.id"
                        :to="'/profile/' + i.id"
                    >
                        <v-chip
                            pill
                        >
                            <v-avatar left>
                                <v-img :src="i.img"> </v-img>
                            </v-avatar>
                            {{i.lastname}}
                        </v-chip>
                    </router-link>
                </template>

                <template #item.initiator="{ item }">
                    <v-chip
                        pill
                        v-if="item.initiator"
                    >
                        <v-avatar left>
                            <v-img :src="item.initiator.img"></v-img>
                        </v-avatar>
                        {{item.initiator.lastname}}

                    </v-chip>
                </template>

                <template #item.deadline="{ item }">
                    <span
                        :class="{'dedline-end': new Date() > new Date(item.deadline)}"
                    >{{item.deadline}}</span>
                </template>
                <template #item.actions="{ item }">
                    <div>
                        <v-btn-toggle
                            v-model="toggle_exclusive"
                            rounded
                            small
                        >

                            <v-btn x-small @click="updateTask(item.id)">
                                <v-icon x-small>mdi-pencil</v-icon>
                            </v-btn>

                            <v-btn x-small @click="deleteTask=item" v-if="item.status_id !== 3">
                                <v-icon x-small>mdi-delete</v-icon>
                            </v-btn>

                            <v-btn x-small @click="pauseTask(item.id)" v-if="item.status_id === 2">
                                <v-icon x-small>mdi-pause</v-icon>
                            </v-btn>

                            <v-btn x-small @click="startTask(item)" v-if="item.status_id !== 2">
                                <v-icon x-small>mdi-play</v-icon>
                            </v-btn>

                            <v-btn x-small @click="taskCompleted(item)" v-if="item.status_id !== 3">
                                <v-icon x-small>mdi-check-bold</v-icon>
                            </v-btn>

                        </v-btn-toggle>
                    </div>
                </template>
            </v-data-table>
            <v-dialog
                v-model="dialogCannotBeCompleted"
                max-width="400px"
            >
                <v-card>
                    <h3 class="pa-4">
                        Задача не может быть завершена, есть незакрытые подзадачи
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
                v-model="dialogCannotBeRestored"
                max-width="400px"
            >
                <v-card>
                    <h3 class="pa-4">
                        Задача не может быть восстановлена, родительская задача завершена
                    </h3>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            text
                            @click="dialogCannotBeRestored = false"
                        >
                            Понятно
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog
                v-model="dialogCannotBeDeleted"
                max-width="400px"
            >
                <v-card>
                    <h3 class="pa-4">
                        Задача не может быть удалена, есть подзадачи
                    </h3>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            text
                            @click="dialogCannotBeDeleted = false"
                        >
                            Понятно
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog
                v-model="dialogDelete"
                max-width="290"
            >
                <v-card>
                    <v-card-title class="text-h5">
                        Вы действительно хотите удалить задачу?
                    </v-card-title>

                    <v-card-actions>

                        <v-btn
                            color="green darken-1"
                            text
                            @click="dialogDelete = false"
                        >
                            Нет
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="green darken-1"
                            text
                            @click="taskDelete(deleteTask)"
                        >
                            Да
                        </v-btn>

                    </v-card-actions>
                </v-card>
            </v-dialog>

            <update-task
                :open="dialogUpdate"
                @close="closeDialog()"
                :updateId = updateId
                :isUpdate = isUpdate
            ></update-task>
        </div>
        <h1>{{title}}</h1>
        <v-row>
            <v-col
                v-for="task in tasks" :key="task.id"
                cols="4"
            >
                <v-card
                    class="task-card pa-4 pb-10 position-relative"
                    outlined
                    elevation="4"
                    :class="task.priority ? 'task-priority' + task.priority.id : 'task-priority'"
                    style="height: 100%;"
                >
                    <router-link :to="'/tasks/task/' + task.id">
                        <h5>{{task.title}}</h5>
                    </router-link>
                    <div v-if="task.startdate">Дата старта: {{ task.startdate | startDate }}</div>
                    <div
                        v-if="task.deadline"
                        :class="{'dedline-end': new Date() > new Date(task.deadline)}"
                    >Дедлайн: {{ task.deadline | startDate }}</div>

                    <div v-if="task.initiator">
                        Инициатор задачи:
                        <v-avatar size="30">
                            <router-link
                                :to="'/profile/' + task.initiator.id"
                                :title="task.initiator.name + ' ' + task.initiator.lastname"
                            >
                                <v-img
                                    :src="task.initiator.img"
                                    width="30"
                                    height="30"
                                ></v-img>
                            </router-link>
                        </v-avatar>
                    </div>
                    <div v-if="task.responsibles">
                        <v-avatar size="30"
                                  v-for="i in task.responsibles"
                                  :key="i.id"
                        >
                            <router-link
                                :to="'/profile/' + i.id"
                                :title="i.name + ' ' + i.lastname"
                            >
                                <v-img
                                    :src="i.img"
                                    width="30"
                                    height="30"
                                ></v-img>
                            </router-link>
                        </v-avatar>
                    </div>
                    <div
                        class="text-center task-priority-text"
                        style="position: absolute; bottom: 0; left: 0; right: 0;"
                    >
                        <v-icon
                            v-if="task.priority && task.priority.id == 4"
                            color="#fff"
                        >mdi-fire</v-icon>
                        {{task.priority ? task.priority.priority : 'Без приоритета' }}
                    </div>

<!--                    <pre>-->
<!--                        {{task}}-->
<!--                    </pre>-->
                </v-card>
            </v-col>
        </v-row>
    </div>

</template>

<script>
import moment from 'moment';
export default {
    name: 'user-tasks',
    props: {
        id: Number,
        title: String,
    },
    watch: {
        id: function (val){
            this.init();
        },
        deleteTask: function (q){
            if (this.deleteTask){
                this.dialogDelete = true;
            }
        },
        dialogDelete: function (q) {
            if(!this.dialogDelete){
                this.deleteTask = null;
            }
        }
    },
    data() {
        return {
            isUpdate: false,
            updateId: null,
            deleteTask: null,
            dialogUpdate: false,
            dialogDelete: false,

            toggle_exclusive: null,

            dialogCannotBeCompleted: false,
            dialogCannotBeRestored: false,
            dialogCannotBeDeleted: false,

            tab: null,
            addTask: false,
            tableloading: true,
            expanded: [],
            singleExpand: true,
            tasks: [],
            search: '',
            headers: [
                {
                    text: 'Название',
                    align: 'start',
                    sortable: true,
                    value: 'title',
                },
                {
                    text: 'Ответственные',
                    value: 'responsibles',
                    sortable: true,
                },
                {
                    text: 'Постановщик',
                    value: 'initiator',
                    sortable: true,
                },
                {
                    text: 'Дедлайн',
                    value: 'deadline',
                    sortable: true,
                },
                {
                    text: 'Приоритет',
                    value: 'priority.priority',
                    sortable: true,
                },
                { text: '', value: 'actions', sortable: false },
            ],
        }
    },
    created(){
        moment.locale('ru');
        this.init();
    },
    methods: {
        init(){
            axios.post(`/api/tasks/statuses/${this.id}`).then(response => {
                this.tasks = response.data;
                this.tableloading = false;
            });
        },
        updateTask(id){
            this.isUpdate = true;
            this.updateId = id;
            this.dialogUpdate = true;
        },
        taskCompleted(task){
            if(task.children.length > 0){
                for(let child of task.children){
                    if(child.status_id !== 3){
                        return this.dialogCannotBeCompleted = true;
                    }
                }
            }
            axios.post(`/api/tasks/completed/${task.id}`).then(response => {
                this.init();
            });
        },
        startTask(task){
            if(task.parent){
                if(task.parent.status_id === 3){
                    return this.dialogCannotBeRestored = true;
                }
            }
            axios.post(`/api/tasks/start/${task.id}`).then(response => {
                this.init();
            });
        },
        pauseTask(id){
            axios.post(`/api/tasks/pause/${id}`).then(response => {
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        taskDelete(task){
            if(task.children.length > 0){
                return this.dialogCannotBeDeleted = true;
            }
            axios.post(`/api/tasks/delete/${task.id}`).then(response => {
                this.deleteTask = null;
                this.dialogDelete = false;
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        closeDialog(data){
            if(!data){
                this.dialogUpdate = false;
                this.updateId = null;
                this.isUpdate = false;
                this.init();
            }
        },
    },
    filters: {
        deadLine: function (date) {
            return moment(date).format('LL');
        },
        startDate: function (date) {
            return moment(date).format('ll');
        }
    }
}
</script>

<style scoped>

    .dedline-end{
        color: red;
    }
    .task-card{
        /*-webkit-box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.3);*/
        /*-moz-box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.3);*/
        /*box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.3);*/
        transition: all 0.25s;
        border-radius: 5px;
    }
    .task-card:hover{
        /*-webkit-box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.6);*/
        /*-moz-box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.6);*/
        /*box-shadow: 4px 4px 8px 0px rgba(34, 60, 80, 0.6);*/

    }
    .task-priority{
        outline: 2px solid #eee;
        outline-offset: -2px;
    }
    .task-priority .task-priority-text{
        color: #000;
        background-color: #eee;
    }
    .task-priority1{
        outline: 2px solid #ccc;
        outline-offset: -2px;
    }
    .task-priority1 .task-priority-text{
        color: #000;
        background-color: #ccc;
    }
    .task-priority2{
        outline: 2px solid orange;
        outline-offset: -2px;
    }
    .task-priority2 .task-priority-text{
        color: #fff;
        background-color: orange;

    }
    .task-priority3{
        outline: 2px solid #ff0000;
        outline-offset: -2px;
    }
    .task-priority3 .task-priority-text{
        color: #fff;
        background-color: #ff0000;
    }
    .task-priority4{
        outline: 5px solid #e80000;
        outline-offset: -5px;
    }
    .task-priority4 .task-priority-text{
        color: #fff;
        background-color: #e80000;
        font-weight: bold;
    }
</style>
