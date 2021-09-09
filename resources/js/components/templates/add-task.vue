<template>

    <form class="col add-task"
        @submit.prevent="submit"
    >

        <div class="bg pa-4 mb-4">
            {{errors.title}}
            <v-text-field
                solo
                label="Название"
                name="title"
                v-model="fields.title"

            ></v-text-field>

            {{errors.text}}
            <v-textarea
                solo
                name="text"
                label="Описание задачи"
                value=""
                v-model="fields.text"

            ></v-textarea>

            {{errors.performers_id}}
            <v-autocomplete
                :items="employees"
                solo
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
                            <v-list-item-subtitle v-html="data.item.position.position"></v-list-item-subtitle>
                        </v-list-item-content>
                    </template>
                </template>
            </v-autocomplete>

            {{errors.initiator}}
            <v-autocomplete
                :items="employees"
                solo
                chips
                label="Инициатор"
                item-text="name"
                item-value="id"
                name="initiator"
                v-model="fields.initiator_id"
            >
                <template v-slot:selection="data">
                    <v-chip
                        v-bind="data.attrs"
                        :input-value="data.selected"
                        close
                        @click="data.select"
                        @click:close="removeInitiator()"
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
                            <v-list-item-subtitle v-html="data.item.position.position"></v-list-item-subtitle>
                        </v-list-item-content>
                    </template>
                </template>
            </v-autocomplete>

            {{errors.priority}}
            <v-select
                :items=priorities
                item-text="priority"
                item-selection="priority"
                item-value="id"
                solo
                label="Приоритет"
                dense
                name="priority_id"
                v-model="fields.priority_id"
            ></v-select>

            {{errors.deadline}}
            <v-menu
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
                        prepend-icon="mdi-calendar"
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
            {{isedit}}
            {{issubtask}}
<!--            {{foredit}}-->
            {{fields}}
            <v-menu
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
                        prepend-icon="mdi-calendar"
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
        <v-btn
            type="submit"
            color="primary"
            large
        >
            {{(isedit == true) ? 'Сохранить изменения' : ''}}
            {{(issubtask == true) ? 'Создать подзадачу'  : ''}}
            {{(isedit !== true && issubtask !==true) ? 'Создать задачу'  : ''}}
        </v-btn>
    </form>
</template>
<script>
export default {
    props: ['parent_id', 'isedit', 'issubtask', 'foredit'],
    data () {
        const srcs = {
            1: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
            2: 'https://cdn.vuetifyjs.com/images/lists/2.jpg',
            3: 'https://cdn.vuetifyjs.com/images/lists/3.jpg',
            4: 'https://cdn.vuetifyjs.com/images/lists/4.jpg',
            5: 'https://cdn.vuetifyjs.com/images/lists/5.jpg',
        }

        return {
            fields: {},
            errors: {},
            autoUpdate: true,
            friends: [],
            isUpdating: false,
            employees: [],
            priorities: [],

            priority: ['Очень высокий', 'Высокий', 'Средний', 'Низкий'],
            title: 'Название',
            date: null,
            date2: null,
            menu: false,
            menu2: false,
        }
    },

    watch: {
        isUpdating (val) {
            if (val) {
                setTimeout(() => (this.isUpdating = false), 3000)
            }
        },
        isedit: function ($val){
            if (this.isedit){
                axios.get('/api/tasks/edit/'+this.parent_id).then(response => {
                    this.fields = response.data;
                    // console.log(this.priorities);
                });
            }
        },
        issubtask: function ($val) {
            if (this.issubtask){
                this.fields = {};
                console.log(this.fields);
                this.fields.parent_id = this.parent_id;
                console.log(this.fields);
            }
        }
    },

    methods: {
        removePerformers (item) {
            // console.log(item);
            const index = this.fields.performers_id.indexOf(item.id);
            // console.log(index);
            if (index >= 0) {
                this.fields.performers_id.splice(index, 1)
            }
        },
        removeInitiator () {
            this.fields.initiator_id = null;
        },
        submit() {
            this.errors = {};

            if(this.isedit == true){
                axios.post(`/api/tasks/update/${this.fields.id}`, this.fields).then(response => {
                    // alert('Задача изменена!');
                    window.location.href = `/tasks/${this.fields.id}`;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            } else {
                axios.post('/api/tasks/create', this.fields).then(response => {
                    // alert('Задача добавлена!!!');
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

            if(this.isedit == true){
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
        axios.get('/api/employees').then(response => {
            this.employees = response.data;
            // console.log(this.employees);
        });
        axios.get('/api/priorities').then(response => {
            this.priorities = response.data;
            // console.log(this.priorities);
        });
        if (this.parent_id){
            this.fields.parent_id = this.parent_id;
        };
    },
}
</script>
