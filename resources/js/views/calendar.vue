<template>
    <div>
        <div
            class="mb-6 d-flex justify-space-between"
        >
            <add-meeting
                v-if="isPermission('create-meeting')"
                :updateId = "updateMeetingId"
                @updateId = "clearId()"
            >
            </add-meeting>

            <v-spacer></v-spacer>

            <add-event
                :updateId = "updateId"
                @updateId = "clearId()"
            ></add-event>
        </div>
        <v-sheet>
            <v-toolbar
                flat
            >
                <v-btn
                    outlined
                    class="mr-4"
                    color="grey darken-2"
                    @click="setToday"
                >
                    Сегодня
                </v-btn>
                <v-btn
                    fab
                    text
                    small
                    color="grey darken-2"
                    @click="prev"
                >
                    <v-icon small>
                        mdi-chevron-left
                    </v-icon>
                </v-btn>
                <v-btn
                    fab
                    text
                    small
                    color="grey darken-2"
                    @click="next"
                >
                    <v-icon small>
                        mdi-chevron-right
                    </v-icon>
                </v-btn>
                <v-toolbar-title v-if="$refs.calendar">
                    {{ $refs.calendar.title }}
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-menu
                    bottom
                    right
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            outlined
                            color="grey darken-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                            <span>{{ typeToLabel[type] }}</span>
                            <v-icon right>
                                mdi-menu-down
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="type = 'day'">
                            <v-list-item-title>День</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'week'">
                            <v-list-item-title>Неделя</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = 'month'">
                            <v-list-item-title>Месяц</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="type = '4day'">
                            <v-list-item-title>4 Дня</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-toolbar>
        </v-sheet>
        <v-sheet>
            <v-calendar
                ref="calendar"
                v-model="focus"
                color="primary"
                :events="events"
                :event-color="getEventColor"
                :type="type"
                @click:event="showEvent"
                @click:more="viewDay"
                @click:date="viewDay"
                @change="updateRange"
                :key="componentKey"
            ></v-calendar>
            <v-menu
                v-model="selectedOpen"
                :close-on-content-click="false"
                :activator="selectedElement"
                offset-x
            >
                <v-card
                    color="grey lighten-4"
                    min-width="350px"
                    flat
                >
                    <v-toolbar
                        :color="selectedEvent.color"
                        dark
                    >
                        <v-toolbar-title
                            v-html="selectedEvent.name"
                        ></v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn
                            v-if="!selectedEvent.isMeeting"
                            icon
                            @click="updateId = selectedEvent.id"
                        >
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn
                            v-if="selectedEvent.isMeeting && !selectedEvent.isCompleted && isPermission('update-meeting')"
                            icon
                            @click="updateMeetingId = selectedEvent.id"
                        >
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn
                            v-if="!selectedEvent.isMeeting"
                            icon
                            @click="deleteId = selectedEvent.id"
                        >
                            <v-icon>mdi-delete-outline</v-icon>
                        </v-btn>
                        <v-btn
                            v-if="selectedEvent.isMeeting  && !selectedEvent.isCompleted && isPermission('delete-meeting')"
                            icon
                            @click="deleteMeetingId = selectedEvent.id"
                        >
                            <v-icon>mdi-delete-outline</v-icon>
                        </v-btn>
                        <v-btn icon
                               @click="selectedOpen = false"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar>
                    <v-card-text
                        v-if="selectedEvent.isMeeting"
                    >
                        <router-link
                            :to="'/meetings/' + selectedEvent.id"
                        >
                            Ссылка на собрание
                        </router-link>
                    </v-card-text>
                    <v-card-text
                        v-if="!selectedEvent.isMeeting"
                    >
                        <p v-html="selectedEvent.text"></p>
                    </v-card-text>
                </v-card>
            </v-menu>
        </v-sheet>


        <v-dialog
            v-model="dialogDelete"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Вы действительно хотите удалить событие?
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
                        v-if="deleteId"
                        color="green darken-1"
                        text
                        @click="deleteEvent"
                    >
                        Да
                    </v-btn>
                    <v-btn
                        v-if="deleteMeetingId"
                        color="green darken-1"
                        text
                        @click="deleteMeeting()"
                    >
                        Да
                    </v-btn>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        data: () => ({
            dialogDelete: false,
            deleteId: null,
            updateId: null,
            deleteMeetingId: null,
            updateMeetingId: null,
            tasks: [],
            focus: '',
            type: 'week',
            typeToLabel: {
                month: 'Месяц',
                week: 'Неделя',
                day: 'День',
                '4day': '4 Дня',
            },
            start: null,
            end: null,
            selectedEvent: {},
            selectedElement: null,
            selectedOpen: false,
            events: [],
            colors: [
                'blue',
                'indigo',
                'deep-purple',
                'cyan',
                'pink',
                'brown',
                'green',
                'orange',
                'grey darken-1',
                'pink darken-3',
                'red darken-4',
                'deep-purple darken-3',
                'indigo darken-2',
                'blue darken-3',
                'blue darken-4',
                'light-blue darken-3',
                'light-blue darken-4',
                'cyan darken-4',
                'teal darken-3',
                'teal darken-4',
                'brown darken-4',
                'brown darken-3',
                'brown darken-2',
                'brown darken-1',
                'blue-grey darken-2',
                'blue-grey darken-3',
                'blue-grey darken-4',
                'grey darken-2',
                'grey darken-3',
                'grey darken-4',
                'blue-grey',
            ],
            componentKey: 0,
        }),
        watch:{
            deleteId: function (q){
                if (this.deleteId){
                    this.dialogDelete = true;
                }
            },
            dialogDelete: function (q){
                if (!this.dialogDelete){
                    this.deleteId = null;
                }
            },
            deleteMeetingId: function (q) {
                if (this.deleteMeetingId){
                    this.dialogDelete = true;
                } else {
                    this.dialogDelete = false;
                }
            },
            calendarIndex: function (q) {
                this.clearId();
            },
        },
        mounted () {
            this.$refs.calendar.checkChange()
        },
        computed:{
            permissions: function (){
                return this.$store.getters['user/permissions'];
            },
            calendarIndex: function(){
                return this.$store.getters['user/calendarIndex']
            }
        },
        methods: {
            isPermission(per){
                let permissions = this.permissions;
                if(String(permissions).indexOf(per) >= 0){
                    return true;
                }
                return false;
            },
            viewDay ({ date }) {
                this.focus = date
                this.type = 'day'
            },
            getEventColor (event) {
                return event.color
            },
            setToday () {
                this.focus = ''
            },
            prev () {
                this.$refs.calendar.prev()
            },
            next () {
                this.$refs.calendar.next()
            },
            showEvent ({ nativeEvent, event }) {
                const open = () => {
                    this.selectedEvent = event
                    this.selectedElement = nativeEvent.target
                    requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
                }

                if (this.selectedOpen) {
                    this.selectedOpen = false
                    requestAnimationFrame(() => requestAnimationFrame(() => open()))
                } else {
                    open()
                }

                nativeEvent.stopPropagation()
            },
            updateRange ({ start, end }) {
                const events = [];
                this.start = start;
                this.end = end;
                axios.post(`/api/calendar/events`, {start: start.date, end: end.date}).then(response => {
                    this.tasks = response.data;
                    for (let task in this.tasks) {
                        events.push({
                            isMeeting: false,
                            id: this.tasks[task].id,
                            name: this.tasks[task].title,
                            text: this.tasks[task].text,
                            start: this.tasks[task].start,
                            end: this.tasks[task].end,
                            color: this.tasks[task].color,
                        })
                    }
                });
                axios.post(`/api/calendar/meetings`, {start: start.date, end: end.date}).then(response => {
                    // this.tasks = response.data;
                    let meetings = response.data;
                    for (let meeting in meetings) {
                        events.push({
                            isMeeting: true,
                            isCompleted: meetings[meeting].completed_at != null ? true : false,
                            name: meetings[meeting].department ? 'Собрание отдела: ' + meetings[meeting].department.department : 'Собрание сотрудников',
                            id: meetings[meeting].id,
                            department: meetings[meeting].department ? meetings[meeting].department.department : '',
                            start: meetings[meeting].start,
                            end: meetings[meeting].end,
                            color: meetings[meeting].color,
                        })
                    }
                });

                this.events = events;
            },
            rnd (a, b) {
                return Math.floor((b - a + 1) * Math.random()) + a
            },
            deleteEvent(){
                axios.post(`/api/calendar/delete/${this.deleteId}`).then(response => {
                    // window.location.reload();
                    this.deleteMeetingId = false;
                    this.clearId();
                });
            },
            deleteMeeting(){
                axios.post(`/api/calendar/meetings/delete/${this.deleteMeetingId}`).then(response => {
                    // window.location.reload();
                    this.deleteMeetingId = false;
                    this.clearId();
                });
            },
            clearId(){
                this.updateId = null;
                this.updateMeetingId = null;
                this.componentKey += 1;
            }

        },
    }
</script>
