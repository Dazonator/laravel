<template>
    <div>
        <h1>{{title}}</h1>
<!--        <pre>{{tasks}}</pre>-->
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

                <template #item.startdate="{ item }">
                    <span v-if="item.startdate" style="white-space: nowrap;">{{item.startdate | deadLine}}</span>
                </template>

                <template #item.deadline="{ item }">
                    <span
                        :class="{'dedline-end': new Date() > new Date(item.deadline)}"
                        style="white-space: nowrap;"
                        v-if="item.deadline"
                    >{{item.deadline | deadLine}}</span>
                </template>

                <template #item.priority="{ item }" >
                    <span v-if="item.priority">{{ item.priority }}</span>
                </template>
                <template #item.actions="{ item }">
                    <div class="d-flex">
<!--                        <v-btn-toggle-->
<!--                            v-model="toggle_exclusive"-->
<!--                            rounded-->
<!--                            small-->
<!--                        >-->

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

<!--                        </v-btn-toggle>-->
                    </div>
                </template>
            </v-data-table>
            <update-task
                v-if="dialogUpdate"
                :open="dialogUpdate"
                @close="closeDialog()"
                :updateId = updateId
                :isUpdate = isUpdate
            ></update-task>
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


        </div>
<!--        <h1>{{title}}</h1>-->

<!--        <v-row>-->
<!--            <v-col-->
<!--                v-for="task in tasks" :key="task.id"-->
<!--                cols="12"-->
<!--                sm="6"-->
<!--                lg="4"-->
<!--                xl="3"-->
<!--            >-->
<!--                <task-card-->
<!--                    :task="task"-->
<!--                    @needInit="init()"-->
<!--                ></task-card>-->
<!--            </v-col>-->
<!--        </v-row>-->
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
    data() {
        return {
            toggle_exclusive: null,

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
                    text: 'Дата старта',
                    value: 'startdate',
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
                    title: 'priority.id',
                    sortable: true,
                    sort: (a, b) => console.log(a, b)
                },
                { text: '', value: 'actions', sortable: false },
            ],
            sortBy: [{ key: 'calories', order: 'asc' }],


            isUpdate: false,
            updateId: null,
            dialogUpdate: false,

            dialogCannotBeCompleted: false,
            dialogCannotBeRestored: false,
            dialogCannotBeDeleted: false,

            deleteTask: null,
            dialogDelete: false,
            fab: false,
        }
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
        closeDialog(data){
            if(!data){
                this.dialogUpdate = false;
                this.updateId = null;
                this.isUpdate = false;
                this.init();
                // this.$emit('needInit');
            }
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
                // this.$emit('needInit');
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
                // this.$emit('needInit');
                this.init();
            });
        },
        pauseTask(id){
            axios.post(`/api/tasks/pause/${id}`).then(response => {
                // this.$emit('needInit');
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
                // this.$emit('needInit');
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    },
    filters: {
        deadLine: function (date) {
            return moment(date).format('DD-MM-YYYY');
        },
        startDate: function (date) {
            return moment(date).format('DD-MM-YYYY');
        }
    }
}
</script>

<style scoped>

</style>
