<template>
    <main class="col py-4">
        <div class="row" v-if="loaded">
            <div class="col team-nav" >
                <div class="">
                    <div class="bg py-6 px-10 mb-2">
                        <h5 class="team-nav__title">ОДЕЖДА</h5>
                        <v-list-item-group
                            color="primary"
                        >
                            <v-list>
                                <v-list-item
                                    v-for="(department, i) in departments"
                                    :key="i"
                                >

                                    <router-link
                                        :to="'/team/' + (i+1)"
                                    >
                                        {{ department.department }}
                                    </router-link>
<!--                                    <v-list-item-title-->
<!--                                        v-text="department.department"-->
<!--                                    </v-list-item-title>-->
                                </v-list-item>
                            </v-list>
                        </v-list-item-group>
                    </div>

                    <add-user></add-user>
                </div>
            </div>
            <component
                v-bind:is="getComponent"
                v-bind:id="postData"
            ></component>
        </div>
    </main>
</template>

<script>
export default {
    props: [],
    watch:{
        '$route.path' (to, from){
            this.userId = Number(this.$route.params.userId);
            this.department = Number(this.$route.params.department);
            this.init();
        },
    },
    data() {
        return {
            getComponent: '',
            postData: [],
            loaded: false,
            departments: [],
            // users: [],
            activeDepartment: 0,
            userId: Number(this.$route.params.userId),
            department: Number(this.$route.params.department),
        }
    },
    created(){
        axios.get('/api/team/departments').then(response => {
            this.departments = response.data;
            this.loaded = true;
        });
        this.init();
    },
    methods: {
        init(){
            if(this.department){
                this.departmentUsers(this.department);
            }
            if(this.userId){
                this.openUserTasks(this.userId);
            }
        },
        departmentUsers (i){
            this.postData = i;
            this.getComponent = 'department-users';
        },
        openUserTasks(i){
            this.postData = i;
            this.getComponent = 'team-user-tasks';
        },
    },
}
</script>
