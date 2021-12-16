<template>

    <form
        class="col add-task"
        @submit.prevent="submit"
        v-if="loaded"
    >
        <div class="bg pa-4 mb-4">


            <h5 class="text-center mb-6">
                {{isTask ? 'Создать задачу' : ''}}
                {{isDistribution ? 'Создать задачу для отдела' : ''}}
            </h5>
            {{errors.title}}
            <v-text-field
                label="Название"
                name="title"
                v-model="fields.title"
            ></v-text-field>

            {{errors.text}}
            <v-textarea
                name="text"
                label="Описание задачи"
                value=""
                v-model="fields.text"

            ></v-textarea>

            {{errors.departments}}
            <v-select
                v-if="isDistribution"
                :items=departments
                item-text="department"
                item-selection="department"
                item-value="id"
                label="Отдел"
                dense
                name="department_id"
                v-model="fields.department_id"
            ></v-select>

            {{errors.performers_id}}
            <v-autocomplete
                v-if="!isDistribution"
                :items="employees"
                chips
                label="Исполнители"
                item-text="name"
                item-value="id"
                multiple
                name="performers_id"
                v-model=fields.performers_id
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

<!--            {{errors.initiator}}-->
<!--            <v-autocomplete-->
<!--                v-if="!isDistribution"-->
<!--                :items="employees"-->
<!--                chips-->
<!--                label="Инициатор"-->
<!--                item-text="name"-->
<!--                item-value="id"-->
<!--                name="initiator"-->
<!--                v-model="fields.initiator_id"-->
<!--            >-->
<!--                <template v-slot:selection="data">-->
<!--                    <v-chip-->
<!--                        v-bind="data.attrs"-->
<!--                        :input-value="data.selected"-->
<!--                        close-->
<!--                        @click="data.select"-->
<!--                        @click:close="removeInitiator()"-->
<!--                    >-->
<!--                        <v-avatar left>-->
<!--                            <v-img :src="data.item.img"></v-img>-->
<!--                        </v-avatar>-->
<!--                        {{ data.item.lastname }}-->
<!--                    </v-chip>-->
<!--                </template>-->
<!--                <template v-slot:item="data">-->
<!--                    <template v-if="typeof data.item !== 'object'">-->
<!--                        <v-list-item-content v-text="data.item"></v-list-item-content>-->
<!--                    </template>-->
<!--                    <template v-else>-->
<!--                        <v-list-item-avatar>-->
<!--                            <v-img-->
<!--                                :src="data.item.img"-->
<!--                                :aspect-ratio="1/1"-->
<!--                            >-->
<!--                            </v-img>-->
<!--                        </v-list-item-avatar>-->
<!--                        <v-list-item-content>-->
<!--                            <v-list-item-title v-html="data.item.name + ' ' + data.item.lastname"></v-list-item-title>-->
<!--                            <v-list-item-subtitle v-html="data.item.position"></v-list-item-subtitle>-->
<!--                        </v-list-item-content>-->
<!--                    </template>-->
<!--                </template>-->
<!--            </v-autocomplete>-->

            {{errors.priority}}
            <v-select
                v-if="!isDistribution"
                :items=priorities
                item-text="priority"
                item-selection="priority"
                item-value="id"
                label="Приоритет"
                dense
                name="priority_id"
                v-model="fields.priority_id"
            ></v-select>



            {{errors.deadline}}
            <v-menu
                v-if="!isDistribution"
                v-model="menu2"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="auto"

            >
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="fields.deadline"
                        label="Дедлайн"
                        append-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="fields.deadline"
                    @input="menu2 = false"
                ></v-date-picker>
            </v-menu>


            {{errors.startdate}}
            <v-menu
                v-if="!isDistribution"
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="auto"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="fields.startdate"
                        label="Дата старта"
                        append-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="fields.startdate"
                    @input="menu = false"
                ></v-date-picker>
            </v-menu>
        </div>
        {{fields}}
        <v-btn
            type="submit"
            color="primary"
            large
            block
        >
            {{isEdit ? 'Сохранить изменения' : ''}}
            {{isSubtask ? 'Создать подзадачу' : ''}}
            {{isTask ? 'Создать задачу' : ''}}
            {{isDistribution ? 'Создать задачу для отдела' : ''}}
        </v-btn>
    </form>
</template>
<script>
export default {
    props: ['parent_id', 'isEdit', 'isSubtask', 'foredit', 'whatCreate'],
    data () {
        const srcs = {
            1: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
            2: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
            3: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
            4: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
            5: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
        }

        return {
            loaded: false,
            isTask: false,
            isDistribution: false,
            fields: {},
            errors: {},
            autoUpdate: true,
            isUpdating: false,
            employees: [],
            priorities: [],
            departments: [],
            steps: [],

            // priority: ['Очень высокий', 'Высокий', 'Средний', 'Низкий'],
            // title: 'Название',
            date: null,
            date2: null,
            menu: false,
            menu2: false,
        }
    },
    computed: {
        // employees: function (){
        //     return this.$store.getters['user/users'];
        // },
        // priorities: function (){
        //     return this.$store.getters['user/priorities'];
        // },
        // departments: function (){
        //     return this.$store.getters['user/departments'];
        // }
    },
    watch: {
        whatCreate: function (q){
            if (this.whatCreate === 'task'){
                this.isTask = true;
                this.isDistribution = false;
            } else if (this.whatCreate === 'distribution'){
                this.isTask = false;
                this.isDistribution = true;
            } else {
                this.isTask = false;
                this.isDistribution = false;
            }
        },
        isEdit: function (q){
            if (this.isEdit){
                axios.post('/api/tasks/edit/'+this.parent_id).then(response => {
                    console.log(response.data);
                    this.fields = response.data;
                    this.steps = this.fields.parent.steps;
                });
            }
        },
        isSubtask: function (q) {
            if (this.isSubtask){
                axios.post('/api/tasks/parent-steps/'+this.parent_id).then(response => {
                    this.steps = response.data;
                });
                this.fields = {};
                this.fields.parent_id = this.parent_id;
            }
        }
    },

    methods: {
        init(){
            if (this.parent_id){
                this.fields.parent_id = this.parent_id;
            }
            if (this.whatCreate === 'task'){
                this.isTask = true;
            }
            if (this.whatCreate === 'distribution'){
                this.isDistribution = true;
            }
            this.loaded = true;
        },
        removePerformers (item) {
            const index = this.fields.performers_id.indexOf(item.id);
            if (index >= 0) {
                this.fields.performers_id.splice(index, 1)
            }
        },
        removeInitiator () {
            this.fields.initiator_id = null;
        },
        submit() {
            this.errors = {};

            if(this.isEdit){
                axios.post(`/api/tasks/update`, this.fields).then(response => {
                    window.location.href = `/tasks/${this.fields.id}`;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            } else if (this.isDistribution){

                axios.post('/api/tasks/create-department-task', this.fields).then(response => {
                    window.location.href = "/tasks";
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });

            } else {
                axios.post('/api/tasks/create', this.fields).then(response => {
                    window.location.href = "/tasks";
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
        },
        deleteTask() {
            this.errors = {};

            if(this.isEdit == true){
                axios.post(`/api/tasks/delete/${this.id}`, this.id).then(response => {
                    alert('Message sent!');
                    window.location.href = "/tasks";
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            }
        },
    },
    created(){
        axios.post('/api/add-task-params').then(response => {
            this.employees = response.data.employees;
            this.priorities = response.data.priorities;
            this.departments = response.data.departments;
        });
        this.init();
    },
}
</script>
