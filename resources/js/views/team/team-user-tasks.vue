<template>
    <v-col
        v-if="loaded"
    >
        <div class="user-inform mb-4">
            <router-link
                :to="'/profile/' + user.id"
            >
                <v-avatar
                    left
                    size="28"
                    class=""
                >
                    <v-img
                        :src=user.img
                        alt=item.name
                    ></v-img>
                </v-avatar>
                {{user.name}} {{user.lastname}}
            </router-link>
        </div>
        <div class="bg pa-4">
            <v-tabs
                v-model="tab"
            >
                <v-tab
                    v-for="status in statuses"
                    :key="status.id"
                >
                    {{ status.status }}
                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
                <v-tab-item
                    v-for="status in statuses"
                    :key="status.id"
                >
                    <v-card flat>
                        <v-simple-table>
                            <template v-slot:default>
                                <thead>
                                    <tr>
                                        <th class="text-left">
                                            Название
                                        </th>
                                        <th class="text-center">
                                            Постановщик
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="item in status.status_tasks"
                                        :key="item.name"
                                    >
                                        <td>
                                            <router-link :to="'/tasks/task/' + item.id">{{ item.title }}</router-link>
                                        </td>
                                        <td class="text-center">
                                            <v-chip
                                                pill
                                            >
                                                <v-avatar left>
                                                    <v-img :src="item.initiator.img"></v-img>
                                                </v-avatar>
                                                {{item.initiator.lastname}}

                                            </v-chip>
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </div>
    </v-col>
</template>

<script>
export default {
    name: "team-user-tasks",
    props: ['id'],
    data(){
        return{
            loaded: false,
            tableLoading: true,
            tab: null,
            user: [],
            data: [],
            statuses: [],

        }
    },
    created() {
        axios.post(`/api/team/users/${this.id}`).then(response => {
            this.data = response.data;
            this.user = this.data.user;
            this.statuses = this.data.status;
            this.loaded = true;
            this.tableLoading = false;
        });
    },
    methods:{

    }
}
</script>

<style scoped>

</style>
