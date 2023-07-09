<script>
$(document).ready(function() {

    // Attachements handling
    ////////////////////////////////////////////////////////////////////////////


    // Modal HTML Creators
    // -----------------------------------------------------------------------------

    const modal_header = (title) => {
        return [
            '<div class="modal-header">',
                '<h4 class="modal-title" id="myModalLabel">',
                    title,
                '</h4>',
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">',
                    '<span aria-hidden="true"></span>',
                '</button>',
            '</div>'
        ].join('\n');
    }


    const upload_file_form = () => {
        return [
            '<form method="POST" id="file_upload_form" name="upload_file_form">',

                '<div class="modal-body">',

                    '<input type="hidden" name="folder" value="'+ altID +'">',

                    '<div class="form-group">',
                        '<label for="title">Τίτλος/Περιγραφή για το Αρχείο</label>',
                        '<input type="text" class="form-control " value="" name="title" id="title" placeholder="Τίτλος για το Αρχείο" required="">',
                    '</div>',

                    '<div class="form-group">',
                        '<label class="control-label" for="file">Εισαγωγή Αρχείου</label>',
                        '<input class="form-control" type="file" id="file" name="file" required="">',
                    '</div>',

                '</div>',

                '<div class="modal-footer">',
                    '<div class="row" style="width: 50%;">',
                        '<div class="form-actions">',
                            '<div class="col col-4">',
                                '<buton type="button" class="btn btn-default js-modal-close col-12" data-bs-dismiss="modal">',
                                    'Κλείσιμο',
                                '</button>',
                            '</div>',
                            '<div class="col-8">',
                                '<button class="btn btn-success col-12" type="submit">Καταχώριση</button>',
                            '</div>',
                        '</div>',
                    '</div>',
                '</div>',

            '</form>'
        ].join('\n');
    }


    const preview_article = (title, intro, body) => {
        return [
            '<div class="modal-body">',
                '<div class="post">',

                    '<h2>'+ title +'</h2>',

                    ((intro == '') ? '' : ('<div class="intro">'+ intro +'</div>')),

                    '<div class="article-body">',
                        marked.parse(body, { sanitize: true }),
                    '</div>',
                
                '</div>',
            '</div>',
            '<div class="modal-footer">',
                '<div class="row" style="width: 50%;">',
                    '<div class="form-actions">',
                        '<div class="col-md-4 col-md-offset-4 col-6 col-offset-3">',
                            '<buton type="button" class="btn btn-default js-modal-close col-12" data-bs-dismiss="modal">',
                                'Κλείσιμο',
                            '</button>',
                        '</div>',
                    '</div>',
                '</div>',
            '</div>'
        ].join('\n');
    }


    /** When modal show-up
     * -------------------------------------------------------------------------
     * ... prepare the manage-category form 
     * ... update select2 with possible parents
     */
    $('#editorModal').on('show.bs.modal', event => {
        let button = $(event.relatedTarget);    // Button that triggered the modal
        let action = button.data('action');     // action
        // var modal = $(this);    // modal object     
        
        // update modal literature

        switch (action) {
            case 'upload_file':
                $("#editorModal .modal-content").html(
                    modal_header('Εισαγωγή Αρχείου') + upload_file_form()
                );
                break;
            
                case 'preview':
                    let preview = preview_article(
                        $('.edit-post form input[name=title]').val(),
                        $('.edit-post form textarea[name=intro]').val(),
                        $('.edit-post form textarea[name=body]').val()
                    );
                    $("#editorModal .modal-content").html( modal_header('Προεπισκόπηση') + preview);
                    break;

            default:
                // do nothing
        }

    });


    /** submit upload file event
     * -------------------------------------------------------------------------
     */
    $('#editorModal').on('submit', 'form[name=upload_file_form]', function (event) {
        event.preventDefault();

        // create the FormData object
        // --- -- -- - - -
        var fd = new FormData(document.getElementById('file_upload_form'));

        // post the form (via ajax)
        $.ajax({
            url: '/admin/api/file_upload',
            type: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {   // on success

                if (response.success) {      
                    files.push({    // ... update files array
                        id: response.id,
                        label: response.title,
                        path: response.path,
                        type: response.type
                    });
                    draw_files_list();                   

                    $('#editorModal').modal('hide');

                } else {
                    console.log('File not uploaded');
                }
            }
        });
    });



});
</script>
