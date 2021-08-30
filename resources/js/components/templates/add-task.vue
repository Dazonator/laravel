<template>
    <form class="col add-task" @submit.prevent="submit">
        <div class="bg pa-4 mb-4">
            <v-text-field
                :disabled="isUpdating"
                solo
                label="Название"
                name="title"
            ></v-text-field>

            <v-textarea
                solo
                name="text"
                label="Описание задачи"
                value=""
            ></v-textarea>

            <v-autocomplete
                v-model="friends"
                :disabled="isUpdating"
                :items="people"
                solo
                chips
                label="Исполнители"
                item-text="name"
                item-value="id"
                multiple
                name="performers"
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
                            <v-img :src="data.item.avatar"></v-img>
                        </v-avatar>
                        {{ data.item.name }}
                    </v-chip>
                </template>
                <template v-slot:item="data">
                    <template v-if="typeof data.item !== 'object'">
                        <v-list-item-content v-text="data.item"></v-list-item-content>
                    </template>
                    <template v-else>
                        <v-list-item-avatar>
                            <img :src="data.item.avatar">
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title v-html="data.item.name"></v-list-item-title>
                            <v-list-item-subtitle v-html="data.item.group"></v-list-item-subtitle>
                        </v-list-item-content>
                    </template>
                </template>
            </v-autocomplete>

            <v-autocomplete
                :disabled="isUpdating"
                :items="people"
                solo
                chips
                label="Инициатор"
                item-text="name"
                item-value="id"
                name="initiator"
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
                            <v-img :src="data.item.avatar"></v-img>
                        </v-avatar>
                        {{ data.item.name }}
                    </v-chip>
                </template>
                <template v-slot:item="data">
                    <template v-if="typeof data.item !== 'object'">
                        <v-list-item-content v-text="data.item"></v-list-item-content>
                    </template>
                    <template v-else>
                        <v-list-item-avatar>
                            <img :src="data.item.avatar">
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <v-list-item-title v-html="data.item.name"></v-list-item-title>
                            <v-list-item-subtitle v-html="data.item.group"></v-list-item-subtitle>
                        </v-list-item-content>
                    </template>
                </template>
            </v-autocomplete>

            <v-select
                :items="priority"
                solo
                label="Приоритет"
                dense
                name="priority"
            ></v-select>


            <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :return-value.sync="date"
                transition="scale-transition"
                offset-y
                min-width="auto"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        solo
                        v-model="date"
                        label="Дедлайн"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        name="deadline"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="date"
                    no-title
                    scrollable
                >
                    <v-spacer></v-spacer>
                    <v-btn
                        text
                        color="primary"
                        @click="menu = false"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        text
                        color="primary"
                        @click="$refs.menu.save(date)"
                    >
                        OK
                    </v-btn>
                </v-date-picker>
            </v-menu>

            <v-menu
                solo
                ref="menu2"
                v-model="menu2"
                :close-on-content-click="false"
                :return-value.sync="date2"
                transition="scale-transition"
                offset-y
                min-width="auto"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="date2"
                        label="Дата старта"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        solo
                        name="startdate"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="date2"
                    no-title
                    scrollable
                >
                    <v-spacer></v-spacer>
                    <v-btn
                        text
                        color="primary"
                        @click="menu2 = false"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        text
                        color="primary"
                        @click="$refs.menu2.save(date2)"
                    >
                        OK
                    </v-btn>
                </v-date-picker>
            </v-menu>
        </div>
        <v-btn
            type="submit"
            block
            large
            color="green accent-3"
            elevation="2"
        >Добавить задачу</v-btn>
    </form>
</template>
<script>
export default {
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
            people: [
                { header: 'Программисты' },
                { name: 'Захаров Андрей', group: 'Программисты', id: 111, avatar: srcs[1] },
                { name: 'Ali Connors', group: 'Программисты', id: 222, avatar: srcs[2] },
                { name: 'Trevor Hansen', group: 'Программисты', id: 333, avatar: srcs[3] },
                { name: 'Tucker Smith', group: 'Программисты', id: 444, avatar: srcs[2] },
                { divider: true },
                { header: 'Дизайнеры' },
                { name: 'Britta Holt', group: 'Дизайнеры', id: 555, avatar: srcs[4] },
                { name: 'Jane Smith ', group: 'Дизайнеры', id: 666, avatar: srcs[5] },
                { name: 'John Smith', group: 'Дизайнеры', id: 777, avatar: srcs[1] },
                { name: 'Sandra Williams', group: 'Дизайнеры', id: 888, avatar: srcs[3] },
            ],
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
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    },
}
</script>
