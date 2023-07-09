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

        } else {
            // prepare data to POST
            var data = {
                    // identification fields
                id: $('input[name=id]').val(),
                invitation: $('input[name=invitation]').val(),
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
                    // access verification
                password: $('input[name=password]').val()
            };
            console.log(data);
        
            var request = '/account/activate';    // set action url

            // send POST request
            $.post(request, data)
            .done(function( data ) {
                $('.office .content-form').html(`<h4>${data.title}<h4><br>${data.message}`)
            });
        }
    });

  });
</script>