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

                <div class="task-page__created">
                    Создано 12/02/2021 Менеджеры №123
                </div>

                <div class="responsibles">
                    <h6 class="responsibles__title">Исполнителей в этой задаче: {{task.responsibles.length}}</h6>
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

                <div class="parent-tasks mb-12" v-if="task.parent">
                    <h5 class="parent-tasks__title">Родительская задача</h5>
                    <div class="parent-tasks-table">
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                <tr>
                                    <td>
                                        <router-link
                                            :to="'/tasks/' + task.parent.id"
                                        >
                                            {{task.parent.title}}
                                        </router-link>
                                    </td>
                                    <td>
                                        {{ task.parent.text }}
                                    </td>
                                </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </div>
                </div>

                <div class="subtasks" v-if="task.children">
                    <h5 class="subtasks__title">Подзадачи</h5>
                    <div class="subtasks-table">
                        <v-simple-table>
                            <template v-slot:default>
                                <tbody>
                                    <tr
                                        v-for="item in task.children"
                                    >
                                        <td>
                                            <router-link
                                                :to="'/tasks/' + item.id"
                                            >
                                                {{item.title}}
                                            </router-link>
                                        </td>
                                        <td>
                                            {{ item.text }}
                                        </td>
                                    </tr>
                                </tbody>
                            </template>
                        </v-simple-table>
                    </div>
                </div>


<!--                <div class="bg pa-4">-->

<!--                </div>-->
            </div>
            <add-task :parent_id="id"></add-task>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            loaded: false,
            id: Number(this.$route.params.id),
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
