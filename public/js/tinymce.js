function initMce(type, dir) {
    var directory = type+"/"+dir;

    tinymce.init({
            selector: "textarea#entry_edit",theme: "modern", height: 600,
            language : "pl",
            content_css : "/css/tinymce.css",
            plugins: [
                 "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                 "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            style_formats: [
                    {title: 'Bible cite', inline: 'span', classes: 'bible-cite'},
                    {title: 'Red cite', inline: 'span', classes: 'red-cite'},
                    {title: 'Table border', selector: 'table', classes: 'table_border'},
                    {
                        title: 'Image 100',
                        selector: 'img',
                        styles: {
                            'max-width': '100%',
                        }
                     },
                    {
                        title: 'Image Left',
                        selector: 'img',
                        styles: {
                            'float': 'left', 
                            'margin': '0 10px 0 0'
                        }
                     },
                     {
                         title: 'Image Right',
                         selector: 'img', 
                         styles: {
                             'float': 'right', 
                             'margin': '0 0 0 10px'
                         }
                     },
                     {
                         title: 'Image Left 50pc',
                         selector: 'img', 
                         styles: {
                             'float': 'left', 
                             'margin': '0 10px 0 0',
                             'max-width': '50%',
                             'height' : 'auto'
                         }
                     },
                     {
                         title: 'Image Right 50pc',
                         selector: 'img', 
                         styles: {
                             'float': 'right', 
                             'margin': '0 0 0 10px',
                             'max-width': '50%',
                             'height' : 'auto'
                         }
                     },
                {title: 'Bold text', inline: 'b'},
                //{title: 'Example 1', inline: 'span', classes: 'bible-cite'},
                //{title: 'Example 2', inline: 'span', classes: 'red-cite'},
                {title: 'Table styles'},
                {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
            ],
            toolbar1: " undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code",
            image_advtab: true ,
            convert_urls: true,
            relative_urls: false,
            external_filemanager_path: "/filemanager/",
            filemanager_title:"Menadżer zawartości",
            external_plugins: { "filemanager" : "/filemanager/plugin.min.js" }
    });

    tinymce.init({
            selector: "textarea#content_msg",theme: "modern", height: 300, width: 720, menubar: false,
            language : "pl",
            content_css : "/css/tinymce.css",
            plugins: [
                 "advlist autolink link image lists charmap hr pagebreak",
                 "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                 "paste"
       ],
            toolbar1: " undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | link unlink | image",
            image_advtab: true ,
    });
}