<template>
    <form class="col add-task" @submit.prevent="submit">
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
                        @click:close="remove(data.item)"
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
                        @click:close="remove(data.item)"
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
            {{parent_id}}
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
        >Добавить задачу</v-btn>
    </form>
</template>
<script>
export default {
    props: {
        parent_id: Number
    },
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
    },

    methods: {
        remove (item) {
            const index = this.friends.indexOf(item.name)
            if (index >= 0) this.friends.splice(index, 1)
        },
        submit() {
            this.errors = {};

            axios.post('/api/add-task', this.fields).then(response => {
                alert('Message sent!');
                window.location.href = "/tasks"
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
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
