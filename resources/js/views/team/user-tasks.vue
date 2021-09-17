<template>
    <div class="col">
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
        <div class="bg pa-4" v-if="loaded">
            Страница задач пользователя {{data}}
            <v-tabs
                v-model="tab"
            >
                <v-tab
                    v-for="n in items"
                    :key="n"
                >
                    {{ n }}
                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
                <v-tab-item
                    v-for="item in items"
                    :key="item"
                >
                    <v-card flat>
<!--                        <v-card-text v-text="awdwad"></v-card-text>-->
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </div>
    </div>
</template>

<script>
export default {
    name: "user-tasks",
    props: ['data'],
    data(){
        return{
            loaded: false,
            tableLoading: true,
            tab: null,
            user: [],
            items: [
                'В работе', 'Планы', 'Выполнено',
            ],

        }
    },
    created() {
        axios.get(`/api/team/users/${this.data}`).then(response => {
            this.user = response.data;
            this.loaded = true;
            this.tableLoading = false;
            console.log(response.data);
        });
    },
    methods:{

    }
}
</script>

<style scoped>

</style>
