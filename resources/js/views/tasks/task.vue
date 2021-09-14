<template>
    <main class="col py-4">
        <div class="row">
            <div class="col task-page"  v-if="loaded">
                <h1>{{task.title}}</h1>
                <!--            <pre v-html="tasks"></pre>-->
                <div class="task-page__deadline">
                    Дедлайн  25 января 2021
                </div>


                <div class="task-page__status">
                    <span>{{task.status.status}}</span>
                </div>

                <div class="task-page__priority">
                    <span>{{task.priority.priority}}</span>
                </div>

                <p class="task-page__text">
                    {{task.text}}
                </p>

                <div class="task-page__created">
                    Создано 12/02/2021 Менеджеры №123
                </div>

                <div class="responsibles">
                    <h6 class="responsibles__title">Исполнителей в этой задаче: {{task.responsibles.length}}</h6>
                    <ul class="responsibles__items">
                        <li v-for="item in task.responsibles">
                            <a href="">
                                <v-avatar
                                    left
                                    size="28"
                                >
                                    <v-img
                                        :src=item.img
                                        alt=item.name
                                    ></v-img>
                                </v-avatar>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="mb-6">

                    <v-btn
                        color="success"
                        @click.stop="taskCompleted()"
                        :disabled="!statusActive"
                    >
                        {{(!statusActive) ? 'Задача завершена' : 'Завершить задачу'}}
                    </v-btn>

                    <v-btn
                        v-if="!statusActive"
                        color="primary"
                        @click="restoreTask()"
                    >
                        Восстановить задачу
                    </v-btn>

                    <v-btn
                        v-if="statusActive"
                        color="yellow darken-3"
                        dark
                        @click="edit()"
                    >
                        Редактировать
                    </v-btn>

                    <v-btn
                        v-if="statusActive"
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
                                            :to="'/tasks/' + item.id"
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
                                                :to="'/tasks/' + task.parent.id"
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

<!--                <div class="bg pa-4">-->

<!--                </div>-->
            </div>

            <add-task
                v-show="showform"
                :isedit="isedit"
                :issubtask="issubtask"
                :parent_id="id"
                :foredit="foredit"
            ></add-task>

        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            statusActive: true,
            errors: {},
            dialogCannotBeCompleted: false,
            isedit: false,
            issubtask: false,
            foredit:[],
            loaded: false,
            showform: false,
            id: Number(this.$route.params.id),
            task: [],
        }
    },
    created(){
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
            axios.get(`/api/tasks/${this.id}`).then(response => {
                console.log(response.data);
                this.task = response.data;
                this.loaded = true;
                if(this.task.status_id === 3){
                    this.statusActive = false;
                }
            });
        },
        edit(){
            this.isedit = true;
            this.issubtask = false;
            this.foredit = this.task;
            this.showform = true;
        },
        subtask(){
            this.isedit = false;
            this.issubtask = true;
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
                alert('Задача добавлена!!!');
                window.location.href = `/tasks/${this.task.id}`;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        }


    },
}
</script>
