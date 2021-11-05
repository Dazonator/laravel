<template>
    <div>
        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig" @input="postData"></ckeditor>
        {{editorData}}
    </div>
</template>

<script>

    import CKEditor from '@ckeditor/ckeditor5-vue2';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: '',
        props: ['defaultText'],
        watch: {
            defaultText: function (q) {
                if(this.defaultText){
                    this.editorData = this.defaultText;
                }
            }
        },
        data() {
            return {
                editor: ClassicEditor,
                editorData: '',
                editorConfig: {
                    placeholder: 'Текст',
                }
            }
        },
        components: {
            ckeditor: CKEditor.component
        },

        methods: {
            postData(data){
                this.$emit('input', this.editorData);
            },
        }
    }
</script>
<style>
    .ck.ck-reset_all, .ck.ck-reset_all *{
        display: none;
    }
</style>
