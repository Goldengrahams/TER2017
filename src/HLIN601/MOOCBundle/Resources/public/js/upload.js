//je récupère l'action où sera traité l'upload en PHP
var _actionToDropZone = $("#form_snippet_image").attr('action');

//je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
Dropzone.autoDiscover = false;
var myDropzone = new Dropzone("#form_snippet_image", { url: _actionToDropZone });

myDropzone.on("addedfile", function(file) {
            alert('nouveau fichier reçu');
        });