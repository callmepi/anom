<script>
    $(document).ready(function() {

        // application details
        ////////////////////////////////////////////////////////////////////////

        function postObject() {
            return {        // select content data
                subject: $('input[name=subject]').val(),
                date: $('input[name=date]').val(),
                type_id: 1,     // 1 = application document
                first_name: $('input[name=first_name]').val(),
                last_name: $('input[name=last_name]').val(),
                father_name: $('input[name=father_name]').val(),
                phone: $('input[name=phone]').val(),
                email: $('input[name=email]').val(),
                registration_number: $('input[name=registration_number]').val(),
                belonging_school: $('input[name=belonging_school]').val(),
                date: $('input[name=date]').val(),
                sector: selected_text($('select[name=sector]')),
                position: selected_text($('select[name=position]')),
                request: $('textarea[name=request]').val(),
                now: Math.floor(Date.now() / 1000),     // client timestamp
                ticket: ref.ticket,
                ref: ref
            };
        }



        // When form is submited
        ////////////////////////////////////////////////////////////////////////

        $('.content-form form').submit( event => {
            event.preventDefault();

            // select action url (add or update)
            var request = '/petition/new';

            // send POST request
            $.post(request, postObject())
            .done(function( data ) {
                console.log(data);

                if (data.success) {   // ok ? redirect to panel
                    window.location.href = '/panel';

                } else {    // error ? alert!
                    alert(data.error);
                }

            });

        });



        // preview event
        ////////////////////////////////////////////////////////////////////////

        $('.btn-preview').click( event => {
            event.preventDefault();
            console.log('preview', postObject());

            pdfMake.fonts = {
                FiraSans: {
                    normal: '<?=SITE_URL?>/assets/fonts/FiraSans-Light.ttf',
                    bold: '<?=SITE_URL?>/assets/fonts/FiraSans-Medium.ttf'
                    // italics: 'https://example.com/fonts/fontFile3.ttf',
                    // bolditalics: 'https://example.com/fonts/fontFile4.ttf'
                },
                FiraMono: {
                    normal: '<?=SITE_URL?>/assets/fonts/FiraMono-Regular.ttf'
                }
            }

            pdfMake.createPdf(designer( postObject(), ref )).open();
        });



        // go-back event
        ////////////////////////////////////////////////////////////////////////

        $('.btn-back2panel').click( event => {
            event.preventDefault();

            let confirm_exit = confirm('<?=CONFIRM_EXIT?>');
            if (confirm_exit) {
                window.location.href = '/panel';
            }
        });


    });
</script>
