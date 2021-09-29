<template>
    <main class="col py-4">
        <h1 class="mb-8">Календарь</h1>

        <add-event></add-event>
        <calendar-component></calendar-component>
    </main>
</template>
<script>
export default {
    data: () => ({
        showAddEvent: false,
    }),

}
</script>
<style>
main{
    height: 100vh;
    overflow-y: auto;
}
</style>
