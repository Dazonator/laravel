<template>
    <main class="col py-4">
        <h1 class="mb-8">Календарь</h1>
        <div class="mb-4">
            <add-meeting></add-meeting>
        </div>

        <calendar-component></calendar-component>
    </main>
</template>
<script>
export default {
    data: () => ({
        showAddEvent: false,
    }),
    created() {
    },
    methods: {
    }
}
</script>
<style>
main{
    height: 100vh;
    overflow-y: auto;
}
</style>
