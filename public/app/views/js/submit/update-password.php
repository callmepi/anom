<script>
  $(document).ready(function() {

    // When form is submited
    ////////////////////////////////////////////////////////////////////////////

    $('form').submit( event => {
        event.preventDefault();

        if ($('input[name=password]').val() != $('input[name=confirm_password]').val()) {
            alert('Τα δυο κωδικοί πρόσβασης δεν ταιριάζουν!');
          
        } else if ($('input[name=password]').val() == "") {
            alert('Ο κωδικός πρόσβασης δεν μπορεί να είναι κενός')

        } else if ( ($('input[name=password]').val() == '0')
                  || ($('input[name=password]').val() =='') ) {
            alert('Παρκαλώ συμπληρώστε ένα τηλέφωνο επικοινωνίας')

        } else if ($('input[name=oldpass]').val() == "") {
            alert('Παρκαλώ συμπληρώστε τον τρέχοντα κωδικό πρόβασης')

        } else {

            // prepare data to POST
            var data = {
                    // identification fields
                id: $('input[name=id]').val(),
                    // data fields
                oldpass: $('input[name=oldpass]').val(),
                password: $('input[name=password]').val(),
            };
            console.log(data);
        
            var request = '/user/update/password';    // set action url

            // send POST request
            $.post(request, data)
            .done(function( data ) {
                $('.office .content-form').html(`<h4>${data.title}<h4><br>${data.message}`)
            });
        }

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