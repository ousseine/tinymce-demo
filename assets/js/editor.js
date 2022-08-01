const form = document.getElementById('tinymce_editor')

tinymce.init({
    selector: 'textarea#post_content',
    plugins: 'advcode casechange formatpainter image editimage linkchecker autolink lists checklist advlist ' +
        'codesample media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
    toolbar: 'undo redo | alignleft aligncenter alignright alignjustify | bullist numlist checklist outdent indent ' +
        '| casechange editimage | showcomments help',
    toolbar_mode: 'floating',

    /* Image treatment */
    automatic_uploads: true,
    images_upload_url: '/post/attachment/' + form.dataset.postId, // url -> post_id
    file_picker_types: 'image',
    /* and here's our custom image picker */
    file_picker_callback: (cb, value, meta) => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];

            const reader = new FileReader();
            reader.addEventListener('load', () => {
                const id = 'blobid' + (new Date()).getTime();
                const blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                const base64 = reader.result.split(',')[1];
                const blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            });
            reader.readAsDataURL(file);
        });

        input.click();
    },

    tinycomments_mode: 'embedded',
    tinycomments_author: 'Ousseineweb',
});