<script>
  $(document).ready(function() {

    // When form is submited
    ////////////////////////////////////////////////////////////////////////////

    $('form').submit( event => {
        event.preventDefault();

        // prepare data to POST
        var data = {
                // identification fields
            id: $('input[name=id]').val(),
                // data fields
            first_name: $('input[name=first_name]').val(),
            last_name: $('input[name=last_name]').val(),
            father_name: $('input[name=father_name]').val(),
            email: $('input[name=email]').val(),
            registration_number: $('input[name=registration_number]').val(),
            sector: $('select[name=sector]').select2('data')[0].text,
            belonging_school: $('input[name=belonging_school]').val(),
            position: $('select[name=position]').select2('data')[0].text,
            phone: $('input[name=phone]').val(),
        };
        console.log(data);
    
        var request = '/user/update/properties';    // set action url

        // send POST request
        $.post(request, data)
        .done(function( data ) {
            $('.office .content-form').html(`<h4>${data.title}<h4><br>${data.message}`)
        });

    });



    // go-back event
    ////////////////////////////////////////////////////////////////////////////

    $('.btn-back2panel').click( event => {
        event.preventDefault();

        let confirm_exit = confirm('<?=CONFIRM_EXIT?>');
        if (confirm_exit) {
            window.location.href = '/panel';
        }
    });

  });
</script>