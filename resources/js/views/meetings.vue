<template>
    <div v-if="loaded">
        <div class="mb-4">
            <add-meeting
                v-if="isPermission('create-meeting')"
            ></add-meeting>
        </div>
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
    </div>
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
            init(){
                axios.post(`/api/meetings`).then(response => {
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
