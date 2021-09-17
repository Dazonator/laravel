<template>
    <div class="col" v-if="loaded">
        <div class="user-inform mb-4">

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
                                        id
                                    </th>
                                    <th class="text-left">
                                        title
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    v-for="item in status.status_tasks"
                                    :key="item.name"
                                >
                                    <td>{{ item.id }}</td>
                                    <td>
                                        <router-link :to="'/tasks/' + item.id">{{ item.title }}</router-link>
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </div>
    </div>
</template>

<script>
export default {
    name: "user-tasks",
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
        axios.get(`/api/team/users/${this.id}`).then(response => {
            // console.log(response.data.user);

            this.data = response.data;
            this.user = this.data.user;
            this.statuses = this.data.status;
            this.loaded = true;
            this.tableLoading = false;
            console.log(this.data.status);
        });
    },
    methods:{

    }
}
</script>

<style scoped>

</style>
