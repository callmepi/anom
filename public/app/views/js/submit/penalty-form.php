<script>
    $(document).ready(function() {

        // penalty details
        ////////////////////////////////////////////////////////////////////////

        function postObject() {
            return {        // select content data
                type_id: 2,     // 1 = penalty document
                subject: $('input[name=subject]').val(),
                of_article: selected_text($('select[name=of_article]')),
                student_names: $('textarea[name=student_names]').val(),
                of_department: $('input[name=of_department]').val(),
                date: $('input[name=date]').val(),
                time: $('input[name=time]').val(),
                place: selected_text($('select[name=place]')),
                rapporteur: selected_text($('select[name=rapporteur]')),
                charge: $('textarea[name=charge]').val(),
                apology: $('textarea[name=apology]').val(),
                decision_reasoning: $('textarea[name=decision_reasoning]').val(),
                decision: selected_text($('select[name=decision]')),
                decision_more: $('input[name=decision_more]').val(),
                president: selected_text($('select[name=president]')),
                now: Math.floor(Date.now() / 1000),     // client timestamp
                members: selected_multitexts($('select[name="members[]"]')),
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
                    normal: '<?=SITE_URL?>/assets/fonts/FiraMono-Regular.ttf',
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
