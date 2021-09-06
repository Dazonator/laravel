<template>

    <main class="col py-4">
        <div class="row">
            <div class="col task-page"  v-if="loaded">
                <h1>{{task.title}}</h1>
                <!--            <pre v-html="tasks"></pre>-->
                <div class="task-page__deadline">
                    Дедлайн  25 января 2021
                </div>

                <div class="task-page__priority">
                    <span>{{task.priority.priority}}</span>
                </div>

                <p class="task-page__text">
                    {{task.text}}
                </p>

                <div class="">
                    Создано 12/02/2021 Менеджеры №123
                </div>

                <div class="responsibles">
                    <h6 class="responsibles__title">7 исполнителей в этой задаче</h6>
                    <ul class="responsibles__items">
                        <li v-for="item in task.responsibles">
                            <a href="">
                                <v-avatar
                                    left
                                    size="28"
                                >
                                    <v-img
                                        :src=item.img
                                        alt=item.name
                                    ></v-img>
                                </v-avatar>
                            </a>
                        </li>
                    </ul>
                </div>

<!--                <div class="bg pa-4">-->

<!--                </div>-->
            </div>
            <add-task></add-task>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            loaded: false,
            id: this.$route.params.id,
            task: [],
        }
    },
    created(){
        axios.get(`/api/tasks/${this.id}`).then(response => {
            console.log(response.data);
            this.task = response.data;
            this.loaded = true;
        });
    },
    methods: {

    },
}
</script>
