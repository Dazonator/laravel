<template>
    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>

    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: '',

        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    // The configuration of the editor.
                    placeholder: 'Текст',
                }
            }
        },

        methods: {
        }
    }
</script>
<style>
    .ck.ck-reset_all, .ck.ck-reset_all *{
        display: none;
    }
</style>
