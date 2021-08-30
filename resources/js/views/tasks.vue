<template>

    <main class="col py-4">
        <div>
            <h5></h5>
<!--            <pre v-html="tasks"></pre>-->
            <div v-for="task in tasks">
                {{ task }}
            </div>
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
    created(){
        axios.get('/api/tasks').then(response => {
            console.log(response.data);
            this.tasks = response.data;
        });
    },
    methods: {
    },
}
</script>
