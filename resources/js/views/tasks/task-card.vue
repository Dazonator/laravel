<template>
 <div style="height: 100%">
     <div style="height: 100%">
         <v-card
             class="task-card px-4 pt-4 pb-10 position-relative d-flex flex-column justify-space-between"
             outlined
             elevation="1"
             :class="task.priority ? 'task-priority' + task.priority.id : 'task-priority'"
             style="height: 100%;"
             :to="'/tasks/task/' + task.id"
         >

             <div>
                 <div class="mb-2">
                        <span
                            v-if="task.deadline"
                            class="deadline"
                            :class="{'deadline-end': new Date() > new Date(task.deadline)}"
                            style="white-space: nowrap;"
                        >
                            {{ task.deadline | deadLine }}
                        </span>
                     <span
                         v-if="task.priority"
                         class="priority"
                         :class="'priority-' + task.priority.id"
                         style="white-space: nowrap;"
                     >
                            {{task.priority.priority}}
                            <v-icon
                                v-if=" task.priority.id == 4"
                                color="#fff"
                            >mdi-fire</v-icon>
                        </span>
                 </div>
                 <router-link :to="'/tasks/task/' + task.id">
                     <h5>{{task.title}}</h5>
                 </router-link>
                 <div v-if="task.startdate">Дата старта: {{ task.startdate | startDate }}</div>
                 <div v-if="task.initiator">
                     Инициатор задачи:
                     <router-link
                         :to="'/profile/' + task.initiator.id"
                         :title="task.initiator.name + ' ' + task.initiator.lastname"
                     >
                         {{task.initiator.name + ' ' + task.initiator.lastname}}
                     </router-link>
                 </div>
             </div>

            <div>
                <div v-if="task.responsibles" class="mt-4 d-flex flex-row-reverse">
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
             </div>
         </v-card>
         <div class="task-card-actionbar d-flex justify-space-around mt-2">

             <v-btn
                 small
                 icon
                 @click="updateTask(task.id)"
             >
                 <v-icon>mdi-pencil</v-icon>
             </v-btn>

             <v-btn
                 small
                 icon
                 @click="deleteTask=task"
                 v-if="task.status_id !== 3"
             >
                 <v-icon>mdi-delete</v-icon>
             </v-btn>

             <v-btn
                 small
                 icon
                 @click="pauseTask(task.id)"
                 v-if="task.status_id === 2"
             >
                 <v-icon>mdi-pause</v-icon>
             </v-btn>

             <v-btn
                 small
                 icon
                 @click="startTask(task)"
                 v-if="task.status_id !== 2"
             >
                 <v-icon>mdi-play</v-icon>
             </v-btn>

             <v-btn
                 small
                 icon
                 @click="taskCompleted(task)"
                 v-if="task.status_id !== 3"
             >
                 <v-icon>mdi-check-bold</v-icon>
             </v-btn>
         </div>
 </div>
     <update-task
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
</template>

<script>
    import moment from "moment";

    export default {
        name: "task-card",
        props: {
            task: Object,
        },
        watch: {
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
                dialogUpdate: false,

                dialogCannotBeCompleted: false,
                dialogCannotBeRestored: false,
                dialogCannotBeDeleted: false,

                deleteTask: null,
                dialogDelete: false,
                fab: false,
            }
        },
        created(){
            moment.locale('ru');
        },
        filters: {
            deadLine: function (date) {
                return moment(date).format('L');
            },
            startDate: function (date) {
                return moment(date).format('ll');
            }
        },
        methods: {
            closeDialog(data){
                if(!data){
                    this.dialogUpdate = false;
                    this.updateId = null;
                    this.isUpdate = false;
                    // this.init();
                    this.$emit('needInit');
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
                    this.$emit('needInit');
                });
            },
            startTask(task){
                if(task.parent){
                    if(task.parent.status_id === 3){
                        return this.dialogCannotBeRestored = true;
                    }
                }
                axios.post(`/api/tasks/start/${task.id}`).then(response => {
                    this.$emit('needInit');
                });
            },
            pauseTask(id){
                axios.post(`/api/tasks/pause/${id}`).then(response => {
                    this.$emit('needInit');
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
                    this.$emit('needInit');
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },
        },
    }
</script>

<style scoped>
    .task-card-actionbar{
        position: absolute;
        bottom: 16px;
        right: 16px;
        left: 16px;
        z-index: 1;
    }
</style>
