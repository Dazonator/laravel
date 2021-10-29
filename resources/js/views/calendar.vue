<template>
    <main class="col py-4">
        <h1 class="mb-8">Календарь</h1>
        <div class="mb-4">
            <v-dialog
                v-model="dialog2"
                persistent
                max-width="600px"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        color="primary"
                        dark
                        v-bind="attrs"
                        v-on="on"
                    >
                        Создать собрание
                    </v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="text-h5">User Profile</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    <v-text-field
                                        label="Номер собрания"
                                        type="number"
                                        value="24"
                                        disabled
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                >
                                    <v-select
                                        :items="departments"
                                        item-text="department"
                                        item-selection="department"
                                        item-value="id"
                                        label="Отдел"
                                    ></v-select>
                                </v-col>
                                <v-col
                                    cols="12"
                                >
                                    <v-autocomplete
                                        :items="users"
                                        chips
                                        label="Дополнительные сотрудники"
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
                                </v-col>

                                <v-col cols="12">
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
                                                v-model="fields.startDate"
                                                label="Дата начала"
                                                prepend-icon="mdi-calendar"
                                                name="startDate"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="fields.startDate"
                                            first-day-of-week=1
                                            @input="menu = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="6">
                                    <v-menu
                                        ref="timeMenu"
                                        v-model="timeMenu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="time"
                                        prepend-icon=false
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="fields.startTime"
                                                label="Время начала"
                                                prepend-icon="mdi-clock-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                name="startTime"
                                            ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-if="timeMenu"
                                            v-model="fields.startTime"
                                            format="24hr"
                                            full-width
                                            @click:minute="$refs.timeMenu.save(time)"
                                        ></v-time-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="6">
                                    <v-menu
                                        ref="timeMenu2"
                                        v-model="timeMenu2"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        :return-value.sync="time2"
                                        transition="Время окончания"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-text-field
                                                v-model="fields.endTime"
                                                label="Время окончания"
                                                prepend-icon="mdi-clock-outline"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                name="endTime"
                                            ></v-text-field>
                                        </template>
                                        <v-time-picker
                                            v-if="timeMenu2"
                                            v-model="fields.endTime"
                                            format="24hr"
                                            full-width
                                            @click:minute="$refs.timeMenu2.save(time2)"
                                        ></v-time-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="dialog2 = false"
                        >
                            Close
                        </v-btn>
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="dialog2 = false"
                        >
                            Save
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>

        <calendar-component></calendar-component>
    </main>
</template>
<script>
export default {
    data: () => ({
        fields: {
            startDate: '',
            endDate: ''
        },
        dialog2: false,
        showAddEvent: false,
        departments: {},
        users: {},
        date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
        menu: false,
        timeMenu: false,
        timeMenu2: false,
        time: null,
        time2: null,
    }),
    created() {
        this.init();
    },
    methods: {
        init(){
            axios.get(`/api/departments`).then(response => {
                this.departments = response.data;
            });
            axios.post(`/api/meetings-users`).then(response => {
                this.users = response.data;
            });
        },
        removePerformers (item) {
            const index = this.fields.performers_id.indexOf(item.id);
            if (index >= 0) {
                this.fields.performers_id.splice(index, 1)
            }
        },
    }
}
</script>
<style>
main{
    height: 100vh;
    overflow-y: auto;
}
</style>
