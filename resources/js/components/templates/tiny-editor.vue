<!--<template>-->
<!--    <editor-->
<!--        api-key="no-api-key"-->
<!--        :init="{-->
<!--            height: 500,-->
<!--            menubar: false,-->
<!--            language: 'ru',-->
<!--            plugins: [-->
<!--                'advlist autolink lists link image charmap print preview anchor',-->
<!--                'searchreplace visualblocks code fullscreen',-->
<!--                'insertdatetime media table paste code help wordcount'-->
<!--            ],-->
<!--            toolbar:-->
<!--                'undo redo | formatselect | bold italic backcolor | \-->
<!--                alignleft aligncenter alignright alignjustify | \-->
<!--                bullist numlist outdent indent | removeformat | help'-->
<!--        }"-->

<!--    />-->
<!--</template>-->

<!--<script>-->
<!--    import Editor from '@tinymce/tinymce-vue';-->

<!--    export default {-->
<!--        name: 'tiny-editor',-->
<!--        components: {-->
<!--            'editor': Editor-->
<!--        },-->
<!--    }-->
<!--</script>-->

<template>
    <div id="app">
        <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
    </div>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'app',
        data() {
            return {
                editor: ClassicEditor,
                editorData: '<p>Rich-text editor content.</p>',
                editorConfig: {
                    // The configuration of the rich-text editor.
                }
            };
        }
    }
</script>
