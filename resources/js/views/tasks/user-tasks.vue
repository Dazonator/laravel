<template>

    <div>
        <h1>{{title}}</h1>
        <div class="bg pa-4">
            <v-data-table
                :headers="headers"
                :items="tasks"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                show-expand
                :search="search"
                :loading="tableloading"
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

                <template v-slot:expanded-item="{ headers, item }">
                    <td
                        :colspan="headers.length"
                        v-html="item.text"
                    >

                    </td>
                </template>
                <template v-slot:item.actions="{ item }">
                    <div>
                        <v-btn-toggle
                            v-model="toggle_exclusive"
                            rounded
                            small
                        >

                            <v-btn x-small @click="updateTask(item.id)">
                                <v-icon x-small>mdi-pencil</v-icon>
                            </v-btn>

                            <v-btn x-small @click="taskDelete(item)" v-if="item.status_id !== 3">
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

            <update-task
                :open="dialogUpdate"
                @close="closeDialog()"
                :updateId = updateId
                :isUpdate = isUpdate
            ></update-task>
        </div>
    </div>
</template>

<script>
export default {
    name: 'user-tasks',
    props: {
        id: Number,
        title: String,
    },
    watch: {
        id: function (val){
            this.init();
        }
    },
    data() {
        return {
            isUpdate: false,
            updateId: null,
            dialogUpdate: false,

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
                {
                    text: '',
                    value: 'data-table-expand'
                },
            ],
        }
    },
    created(){
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
                alert('Задача завершена!!!');
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
}
</script>

<style scoped>

</style>
