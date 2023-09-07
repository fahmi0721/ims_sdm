tinymce.init({
    selector: "textarea#Lampiran,textarea#Surat",
    theme: "modern",
    height: 500,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons paste textcolor responsivefilemanager",
        "code"
    ],
    image_advtab: false,
    image_dimensions: false,
    image_class_list: [
        {title: 'Responsive', value: 'img-responsive'},
        {title: 'Responsive & Center', value: 'img-responsive text-center'}
    ],

    relative_urls: false,
    browser_spellcheck : true,
    codemirror: {
        indentOnInit: true,
        path: 'CodeMirror'
    },
    toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
    toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code | fontsizeselect",
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    external_filemanager_path: "tinymce/plugins/filemanager/",
    filemanager_title: "Filemanager",
    external_plugins: { "filemanager": "../tinymce/plugins/filemanager/plugin.min.js"} 
    
});