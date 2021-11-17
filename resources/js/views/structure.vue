<template>
    <main class="col py-4">
        <div class="row" v-if="loaded">
            <div class="col team-nav" >
                <div class="bg py-6 px-10 mb-2">
                    aw dwa
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default{
        data(){
            return{
                loaded: false,
            }
        },
        methods: {
            init(){
                this.loaded = true;
            }
        },
        created() {
            this.init();
        }

    }
</script>

<style scoped>
</style>
