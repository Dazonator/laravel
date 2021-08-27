<template>
    <main class="col py-4">
        <div v-for="task in tasks" :key='task.id'>
            <h5>dawd</h5>
           <pre>{{ task.id }}</pre>
           <pre>{{ task.title }}</pre>
            <!-- <pre>{{tasks}}</pre> -->
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            tasks: []
        }
    },
    created() {
        this.axios.get('/add-tasks-get').then(response => {
            console.log(response.data);
            this.tasks = response.data;
        });
    },
    methods: {
    },
    mounted(){
    }
}
</script>
