<template>
    <div>
        <div class="task-page"  v-if="loaded">
            <div
                class="task-header d-flex justify-space-between align-items-center mb-4"
            >
                <h1>{{task.title}} ({{task.status.status}})</h1>
                <v-btn-toggle
                    shaped
                >
                    <v-btn
                        v-if="statusActive"
                        @click="update()"
                    >
                        <v-icon color="warning">mdi-pencil</v-icon>
                    </v-btn>

                    <v-btn
                        @click="dialogDelete = true"
                    >
                        <v-icon color="error">mdi-delete</v-icon>
                    </v-btn>

                    <v-btn
                        v-if="!task.distribution_department && statusActive"
                        :disabled="!statusActive"
                        @click.stop="taskCompleted()"
                    >
                        <v-icon color="success">mdi-check-bold</v-icon>
                    </v-btn>

                    <v-btn
                        @click="pauseTask()"
                        v-if="task.status.id === 2"
                    >
                        <v-icon color="primary">mdi-pause</v-icon>
                    </v-btn>

                    <v-btn
                        @click="startTask()"
                        v-if="task.status.id !== 2"
                    >
                        <v-icon color="primary">mdi-play</v-icon>
                    </v-btn>

                </v-btn-toggle>
                <v-dialog
                    v-model="dialogDelete"
                    max-width="290"
                >
                    <v-card>
                        <v-card-title class="text-h5">
                            Вы действительно хотите удалить Задачу?
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
                                @click="taskDelete()"
                            >
                                Да
                            </v-btn>

                        </v-card-actions>
                    </v-card>
                </v-dialog>

            </div>
            <div class="mb-4">
                <h5
                    :class="{'dedline-end': new Date() > new Date(this.task.deadline)}"
                    v-if="task.deadline"
                >
                    Дедлайн  {{ task.deadline | deadLine }}
                </h5>
                <h5 class="">
                    Создано {{ task.created_at | createDate }}
                </h5>
                <div class="mb-4" v-if="task.priority">
                    <span>Приоритет: {{task.priority.priority}}</span>
                </div>
                <div v-if="task.tests">
                    <v-btn
                        @click="completeTest()"
                        v-if="$store.state.user.authUser.id === task.tests.user_id && !task.tests.is_tested"
                    >
                        Завершить тестирование
                    </v-btn>
                    <span
                        v-if="task.tests.is_tested"
                        class="green--text"
                    >Тестирование завершено</span>
                </div>
                <v-form
                    v-if="!task.tests"
                    ref="form"
                    @submit.prevent="sendForTest"
                    v-model="valid"
                    lazy-validation
                    class="d-flex"
                >
                    <v-autocomplete
                        :items="users"
                        label="Кто тестирует"
                        item-text="name"
                        item-value="id"
                        name="performers_id"
                        v-model=userForTest
                        :rules="[v => !!userForTest || 'Обязательное поле']"
                        required
                        style="max-width: 300px"
                    >
                        <template v-slot:selection="data">
                            <v-chip
                                v-bind="data.attrs"
                                :input-value="data.selected"
                                close
                                @click="data.select"
                                @click:close="removePerformers(data.item)"
                            >
                                <v-avatar left>
                                    <v-img :src="data.item.img"></v-img>
                                </v-avatar>
                                {{ data.item.lastname }}
                            </v-chip>
                        </template>
                        <template v-slot:item="data">
                            <template v-if="typeof data.item !== 'object'">
                                <v-list-item-content v-text="data.item"></v-list-item-content>
                            </template>
                            <template v-else>
                                <v-list-item-avatar>
                                    <v-img
                                        :src="data.item.img"
                                        :aspect-ratio="1/1"
                                    >
                                    </v-img>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title v-html="data.item.name + ' ' + data.item.lastname"></v-list-item-title>
                                    <v-list-item-subtitle v-html="data.item.position"></v-list-item-subtitle>
                                </v-list-item-content>
                            </template>
                        </template>
                    </v-autocomplete>

                    <v-btn
                        type="submit"
                        color="primary"
                        large
                    >
                        Отправить на тест
                    </v-btn>
                </v-form>
            </div>

            <div class="bg pa-4 mb-4">
                <h5>Текст задания</h5>
                <div class="task-page__text" v-if="task.text" v-html="task.text"></div>
            </div>



            <div
                class="bg pa-4 mb-4"
                v-if="task.files.length > 0"
            >
                <h5>Файлы</h5>
                <ul>
                    <li
                        v-for="item in task.files"
                        :key="item.id"
                    >
                        <a
                            :href=item.path
                            :download="item.name"

                        >
                            {{item.name}}
                        </a>
                        <v-btn
                            icon
                            color="grey"
                            x-small
                            class="ml-4"
                            @click="deleteFile(item.id)"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </li>
                </ul>
            </div>
            <div class="responsibles bg pa-4 mb-4">
                <h5 class="mb-2">Ответственные</h5>
                <div class="responsibles--items">
                    <v-chip
                        class="mr-2 mb-2"
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

                            {{item.name}} {{item.lastname}}
                        </router-link>
                    </v-chip>
                </div>
            </div>
            <div
                class="subtasks bg mb-4 pa-4"
                v-if=""
            >
                <div
                    class="d-flex justify-space-between mb-4"
                >
                    <h5 class="subtasks__title mr-4">Подзадачи</h5>
                    <v-btn
                        v-if="statusActive && !task.distribution_department"
                        fab
                        x-small
                        icon
                        @click="subtask()"
                    >
                        <v-icon>mdi-plus</v-icon>
                    </v-btn>

                </div>
                <div class="subtasks-table px-4 mb-6">
                    <v-data-table
                        v-if="task.children.length > 0"
                        :headers="headers"
                        :items="task.children"
                        loading-text="Загрузка задач..."
                    >
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
                            <div
                                class="text-right"
                            >
                                <div>
                                    <span>Этап: </span>
                                    <v-select
                                        class="subtasks-input d-inline-block"
                                        :items=task.steps
                                        single-line
                                        label="Этап"
                                        item-text="title"
                                        item-selection="title"
                                        item-value="id"
                                        append-icon="mdi-close"
                                        v-model="item.in_step"
                                        @change="updateTaskStep(item)"
                                        @click:append="deleteTaskStep(item)"
                                    ></v-select>
                                </div>
                            </div>
                        </template>
                    </v-data-table>
                </div>

                <div
                    v-if="task.steps && task.steps.length > 0"
                >
                    <h5 class="subtasks__title mr-4">Этапы</h5>
                    <div
                        v-for="(step, id) in task.steps"
                    >
                        <div class="px-4 d-flex align-items-center">
                            <v-text-field
                                v-model=step.title
                                v-on:blur="updateStep(id)"
                                @keypress.enter="blurInput($event)"
                                style="width: 100%;"
                                class="py-2"
                            ></v-text-field>

                            <v-icon
                                small
                                @click="deleteStep(id)"
                            >
                                mdi-delete
                            </v-icon>
                        </div>
                        <div class="subtasks-table px-4 mb-2">
                            <v-data-table
                                v-if="step.tasks.length > 0"
                                :headers="headers"
                                :items="step.tasks"
                                loading-text="Загрузка задач..."
                            >
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
                                    <div
                                        class="text-right"
                                    >
                                        <div>
                                            <span>Этап: </span>
                                            <v-select
                                                class="subtasks-input d-inline-block"
                                                :items=task.steps
                                                single-line
                                                label="Этап"
                                                item-text="title"
                                                item-selection="title"
                                                item-value="id"
                                                append-icon="mdi-close"
                                                v-model="item.in_step"
                                                @change="updateTaskStep(item)"
                                                @click:append="deleteTaskStep(item)"
                                            ></v-select>
                                        </div>
                                    </div>
                                </template>
                            </v-data-table>
                        </div>
                    </div>

                </div>

                <v-row>
                    <v-col
                        cols="4"
                    >
                        <v-text-field
                            class="mr-2"
                            v-model="dataStep.title"
                            label="Добавить этап"
                            append-icon="mdi-plus"
                            @click:append="addStep"
                            v-on:keyup.enter="addStep"
                            hide-details
                        ></v-text-field>
                    </v-col>
                </v-row>
            </div>

            <div class="parent-tasks bg mb-4 pa-4" v-if="task.parent">
                <h5 class="parent-tasks__title">Родительская задача</h5>
                <div class="parent-tasks-table">
                    <v-simple-table>
                        <template v-slot:default>
                            <tbody>
                            <tr>
                                <td>
                                    Название
                                </td>
                                <td>
                                    Дедлайн
                                </td>
                                <td>
                                    Статус
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <router-link
                                        :to="'/tasks/task/' + task.parent.id"
                                    >
                                        {{task.parent.title}}
                                    </router-link>
                                </td>
                                <td>
                                    {{ task.parent.deadline }}
                                </td>
                                <td>
                                    {{ task.parent.status.status }}
                                </td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </div>
            </div>
            <div class="mb-4">

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
                    v-model="dialogCannotBeRestored"
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
                                        <div class="mb-1" v-html="item.message">
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
                <div class="px-6 py-4">
                    <ck-editor
                        @input="ckeditorText"
                        :defaultText="message"
                    ></ck-editor>
                    <v-btn block color="primary" class="mt-2" @click="sendMessage">
                        Отправить
                    </v-btn>
                </div>
            </div>
        </div>
        <update-task
            :open="dialogUpdate"
            @close="closeDialog()"
            :isSubtask="isSubtask"
            :parent_id="id"
            :updateId = updateId
            :isUpdate = isUpdate
        ></update-task>
    </div>
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
            isSubtask: false,
            isUpdate: false,
            updateId: null,
            // isEdit: false,
            dialogUpdate: false,
            dialogDelete: false,
            deleteId: null,
            chat: {},
            message: '',
            dataStep: {},
            statusActive: true,
            errors: {},
            dialogCannotBeCompleted: false,
            dialogCannotBeRestored: false,
            dialogCannotBeDeleted: false,
            loaded: false,
            // showform: false,
            id: null,
            task: [],
            users: [],
            userForTest: null,
            valid: false,

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
            axios.post(`/api/tasks/task/${this.id}`).then(response => {
                this.task = response.data.task;
                this.users = response.data.users;
                this.loaded = true;
                if(this.task.status_id === 3){
                    this.statusActive = false;
                } else {
                    this.statusActive = true;
                }

            });

            this.initMessages();
        },
        update(){
            this.isSubtask = false;
            this.isUpdate = true;
            this.updateId = this.task.id;
            this.dialogUpdate = true;
        },
        subtask(){
            this.isSubtask = true;
            this.isUpdate = false;
            this.dialogUpdate = true;
            // this.parent_id = this.id;
        },
        taskCompleted(){
            if(this.task.children.length > 0){
                for(let child of this.task.children){
                    if(child.status_id !== 3){
                        return this.dialogCannotBeCompleted = true;
                    }
                }
            }
            if(this.task.steps.length > 0){
                for (let step in this.task.steps) {
                    for (let task in this.task.steps[step].tasks) {
                        if (this.task.steps[step].tasks[task].status_id !== 3){
                            return this.dialogCannotBeCompleted = true;
                        }
                    }
                }
            }

            this.errors = {};
            axios.post(`/api/tasks/completed/${this.id}`).then(response => {
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        startTask(){
            if(this.task.parent){
                if(this.task.parent.status_id === 3){
                    return this.dialogCannotBeRestored = true;
                }
            }
            axios.post(`/api/tasks/start/${this.id}`).then(response => {
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        pauseTask(){

            axios.post(`/api/tasks/pause/${this.id}`).then(response => {
                this.init();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
        taskDelete(){
            if(this.task.children.length > 0){
                return this.dialogCannotBeDeleted = true;
            }
            if(this.task.steps.length > 0){
                for (let step in this.task.steps) {
                    for (let task in this.task.steps[step].tasks) {
                        if (this.task.steps[step].tasks.length > 0){
                            return this.dialogCannotBeDeleted = true;
                        }
                    }
                }
            }

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
        addStep(){
            if (this.dataStep.title !== '' && this.dataStep.title){
                this.dataStep.task_id = this.id;
                axios.post(`/api/tasks/task/create-new-step`, this.dataStep).then(response => {
                    this.init();
                    this.dataStep = {};
                });
            }
        },
        updateStep(id){
            axios.post(`/api/tasks/task/update-step-title`, this.task.steps[id]).then(response => {
                this.init();
            });
        },
        deleteStep(id){
            if(this.task.steps[id].tasks.length > 0){
                alert('Этап не может быть удалён пока в нем есть подзадачи')
            } else {
                axios.post(`/api/tasks/task/delete-step`, this.task.steps[id]).then(response => {
                    this.init();
                });
            }
        },
        blurInput(event){
            event.target.blur();
        },
        updateTaskStep(task){
            axios.post(`/api/tasks/task/update-task-step`, task).then(response => {
                this.init();
            });
        },
        deleteTaskStep(task){
            task.in_step = null;
            this.updateTaskStep(task);
        },
        deleteFile(id){
            axios.post(`/api/tasks/task/delete-file/${id}`).then(response => {
                this.init();
            });
        },
        ckeditorText(data){
            this.message = data;
        },
        closeDialog(data){
            if(!data){
                this.isSubtask = false;
                this.dialogUpdate = false;
                this.updateId = null;
                this.isUpdate = false;
                this.init();
            }
        },
        completeTest(){
            axios.post(`/api/tasks/task/completeTest/${this.id}`).then(response => {
                this.init();
            });
        },
        removePerformers (item) {
            this.userForTest = null;
            this.valid = null;
        },
        sendForTest(){
            if(this.$refs.form.validate()){
                let fields = {
                    user_id: this.userForTest,
                    task_id: this.task.id
                }
                axios.post(`/api/tasks/task/sendForTest`, fields).then(response => {
                    this.init();
                });
            }
        }
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
    .dedline-end{
        color: red;
    }

    .step-title input[type="text"]{
        outline: none;
        box-shadow: none;
        border-bottom: 1px solid #eee;

    }
    .step-title input[type="text"]:focus{
        background-color: #fff;
    }
    .subtasks-input{
        max-width: 200px;
    }

    .task-messages{
        display: flex;
        flex-direction: column;
    }
    .task-messages .v-footer{
        flex-grow: 1;
    }

    .task-messages .row{
        /*height:500px;*/
        /*scroll-behavior: smooth;*/
        min-height: 50px;
        max-height: 500px;
        overflow: auto;
        transition: none;
    }
    .task-messages .container-chat figure img{
        max-width: 100%;
    }
    .task-messages .container-chat figure table{
        max-width: 100%;
    }
    .task-messages .container-chat figure table th,
    .task-messages .container-chat figure table td{
        word-break: break-word;
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
