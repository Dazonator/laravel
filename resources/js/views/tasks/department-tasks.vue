<template>

    <div>
        <h1>{{title}}</h1>
        <div class="bg pa-4">
            <template>
                <v-data-table
                    :headers="headers"
                    :items="tasks"
                    :single-expand="singleExpand"
                    :expanded="expanded"
                    :items-per-page="30"
                    :search="search"
                    :loading="tableloading"
                    loading-text="Загрузка задач..."
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
                    <template #item.title="{ item }">
                        <router-link

                            :to="'/tasks/task/' + item.id"
                        >
                            {{ item.title }}
                        </router-link>
                    </template>
                    <template #item.initiator="{ item }">
                        <v-chip
                            v-if="item.initiator"
                        >
                            <v-avatar left>
                                <v-img :src="item.initiator.img"></v-img>
                            </v-avatar>
                            {{item.initiator.name}}
                            {{item.initiator.lastname}}
                        </v-chip>
                    </template>
                </v-data-table>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: 'department-tasks',
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
            // addTask: false,
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
                    text: 'Постановщик',
                    value: 'initiator',
                    sortable: true,
                },
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
            axios.post(`/api/tasks/department/${this.id}`).then(response => {
                this.tasks = response.data;
                this.tableloading = false;
            });
        }
    },
}
</script>

<style scoped>

</style>
