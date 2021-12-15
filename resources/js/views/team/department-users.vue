<template>
    <v-col>
        <div class="bg pa-4" v-if="loaded">
            <v-data-table
                :headers="headers"
                :items="users"
                :search="search"
                :loading="tableLoading"
                loading-text="Загрузка задач..."
                :hide-default-footer="true"
            >
                <template v-slot:top>
                    <v-toolbar flat>
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
                <template #item.img="{ item }">
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
                </template>
                <template #item.name="{ item }">
                    <router-link
                        v-if="isPermission('view-tasks-other-users')"
                        :to="'/team/users/' + item.id"
                    >
                        {{ item.lastname }} {{ item.name }}
                    </router-link>

                    <router-link
                        v-else
                        :to="'/profile/' + item.id"
                    >
                        {{ item.lastname }} {{ item.name }}
                    </router-link>
                </template>
            </v-data-table>
        </div>
    </v-col>
</template>

<script>
export default {
    name: "department-users",
    props: ['id'],
    watch: {
        id: function (val){
            this.init();
        }
    },
    data(){
        return{
            tableLoading: true,
            search: '',
            loaded: false,
            users: {},
            headers: [
                {
                    text: '',
                    align: 'start',
                    sortable: true,
                    value: 'img',
                },
                {
                    text: 'Имя',
                    align: 'start',
                    sortable: true,
                    value: 'name',
                },
                {
                    text: 'Должность',
                    align: 'start',
                    sortable: true,
                    value: 'position',
                },
            ],
        }

    },

    computed:{
        permissions: function (){
            return this.$store.getters['user/permissions'];
        }
    },
    created() {
        this.init();
    },
    methods: {
        isPermission(per){
            let permissions = this.permissions;
            if(String(permissions).indexOf(per) >= 0){
                return true;
            }
            return false;
        },
        init: function (){
            axios.get(`/api/team/departments/${this.id}`).then(response => {
                this.users = response.data;
                this.loaded = true;
                this.tableLoading = false;
            });
        },
        userTasks: function(value) {
            this.$emit('userTasks', value)
        }
    }

}
</script>

<style scoped>

</style>
