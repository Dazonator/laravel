<template>
    <main class="col py-4" v-if="loaded">
<!--        <div class="row">-->
<!--            <div class="col-3" v-for="(meeting, index) in meetings">-->
<!--                <router-link :to="'/meetings/' + meeting.id" class="meeting">-->
<!--                    <h5>Собрание №{{meeting.id}}</h5>-->
<!--                    <p>{{meeting.department.department}}</p>-->
<!--                </router-link>-->
<!--            </div>-->
<!--        </div>-->
        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="meetings"
                :search="search"
                item-key="id"
                sort-by="number"
                class="elevation-1"
                show-group-by
            >
                <template #item.number="{ item }">
                    <router-link
                        :to="'/meetings/' + item.id"
                    >
                        {{ item.number }}
                    </router-link>
                </template>
            </v-data-table>
        </v-card>
    </main>
</template>

<script>
    export default {
        name: "meetings",
        data(){
            return{
                loaded: false,
                meetings: {},
                search: '',
                headers: [
                    {
                        text: '№',
                        align: 'start',
                        value: 'number',
                        groupable: false,
                    },
                    {
                        text: 'Отдел',
                        value: 'department.department',
                        align: 'start'
                    },
                    {
                        text: 'Начало',
                        value: 'start',
                        align: 'start',
                        groupable: false,
                    },
                    {
                        text: 'Конец',
                        value: 'end',
                        align: 'start',
                        groupable: false,
                    },
                ],
            }
        },
        created() {
            this.init();
        },
        methods: {
            init(){
                axios.post(`/api/meetings`).then(response => {
                    console.log(response.data);
                    this.meetings = response.data;
                    this.loaded = true;
                });
            }
        }
    }
</script>

<style scoped>
    .meeting{
        background-color: #fff;
        border-radius: 20px;
        padding: 20px;
        display: block;
    }
</style>
