<template>
    <main class="col py-4" v-if="loaded">
        <div class="row">
            <div class="col tasks-nav" >
                <div class="">
                    <div class="bg py-6 px-10 mb-2">
                        <v-list-item-group
                            color="primary"
                        >
                            <v-list>

                                <h5 class="team-nav__title">Мои задачи</h5>
                                <v-list-item
                                    v-for="(status, i) in statuses"
                                    :key="i"
                                >
                                    <router-link
                                        :to="'/tasks/status/' + status.id"
                                    >
                                        {{status.status}}
                                    </router-link>
                                </v-list-item>
                                <v-divider></v-divider>
                                <h5 class="team-nav__title">Задачи отделов</h5>
                                <v-list-item
                                    v-for="(department, i) in departments"
                                    :key="'A' + i"
                                >
                                    <router-link
                                        :to="'/tasks/department/' + department.id"
                                    >
                                       {{department.department}}
                                    </router-link>
                                </v-list-item>
                            </v-list>
                        </v-list-item-group>
                    </div>

                    <v-btn
                        color="primary"
                        class="mb-2"
                        @click="newTask"
                        block
                    >
                        Создать задачу
                    </v-btn>

                    <v-btn
                        color="primary"
                        class="mb-2"
                        @click="newDistributionDepartment"
                        block
                    >
                        Создать задачу для отдела
                    </v-btn>
                </div>
            </div>

<!--            <user-tasks-->
<!--                v-if="postId"-->
<!--                :id="postId"-->
<!--                :title="childrenTitle"-->
<!--            >-->
<!--            </user-tasks>-->
            <component
                :is="getComponent"
                :id="postId"
                :title="childrenTitle"
            ></component>

            <add-task
                v-if="addTask"
                :whatCreate="whatCreate"
            ></add-task>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            whatCreate: '',
            loaded: false,
            addTask: false,
            statuses: {},
            departments: {},
            postId: null,
            getComponent: '',
            statusId: Number(this.$route.params.statusId),
            departmentId: Number(this.$route.params.departmentId),
            childrenTitle: '',
            test: [],
        }
    },
    watch:{
        '$route.path' (to, from){
            this.statusId = Number(this.$route.params.statusId);
            this.departmentId = Number(this.$route.params.departmentId);
            this.init();
        }
    },
    created(){
        // axios.get('/api/tasks/statuses').then(response => {
        //     this.statuses = response.data;
        //     // console.log(response.data);
        //     console.log(this.statuses);
        //
        // });
        // axios.get('/api/tasks/departments').then(response => {
        //     this.departments = response.data;
        //     // console.log(this.departments);
        // });
        axios.get('/api/tasks/statuses-departments').then(response => {
            this.statuses = response.data.statuses;
            this.departments = response.data.departments;
            // console.log(this.departments);
            this.init();
            this.loaded = true;
        });


        // this.init();
    },
    methods: {
        init(){
            if(this.statusId){
                this.statusTasks(this.statusId);
            }
            if(this.departmentId){
                this.departmentTasks(this.departmentId);
            }
        },
        statusTasks(i){
            this.postId = i;
            // this.childrenTitle = 'Мои текущие задачи';
            this.childrenTitle = this.statuses[i-1].status;
            this.getComponent = 'user-tasks';
        },
        departmentTasks(i){
            this.postId = i;
            this.childrenTitle = this.departments[i-1].department;
            // this.childrenTitle = 'Задачи отделов';
            this.getComponent = 'department-tasks';
        },
        newTask(){
            if (!this.addTask){
                this.addTask = true;
                this.whatCreate = 'task';
            } else if(this.addTask && this.whatCreate !== 'task') {
                this.whatCreate = 'task';
            } else {
                this.whatCreate = '';
                this.addTask = false;
            }

        },
        newDistributionDepartment(){
            if (!this.addTask){
                this.addTask = true;
                this.whatCreate = 'distribution';
            } else if(this.addTask && this.whatCreate !== 'distribution') {
                this.whatCreate = 'distribution';
            } else {
                this.whatCreate = '';
                this.addTask = false;
            }
        }

    },
}
</script>
