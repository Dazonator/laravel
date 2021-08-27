<template>

    <main class="col py-4">
        <div>
            <h5>dawd</h5>
            <pre v-html="tasks"></pre>
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
        this.axios.get('/tasks').then(response => {
            console.log(response);
            this.tasks = response.data;
        });

    },
    methods: {
    },
    mounted(){
    }
}
</script>
