<template>
    <div>
        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig" @input="postData"></ckeditor>
        {{editorData}}
    </div>
</template>

<script>

    import CKEditor from '@ckeditor/ckeditor5-vue2';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    // import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';
    // import UploadAdapter from './UploadAdapter.js';
    // let uploader = './images';

    export default {
        name: '',

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
            // uploader(editor){
            //     editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
            //         return new UploadAdapter( loader );
            //     };
            // },
        }
    }
</script>
<style>
    .ck.ck-reset_all, .ck.ck-reset_all *{
        display: none;
    }
</style>
