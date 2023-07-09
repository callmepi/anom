<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Είσοδος Χρήστη</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes',
          [ 'no_dataTables' => true, 'no_pdfmake' => true ]
        );
    ?>
<style>
:root {   /* login-form overides */
    --form-content-font-size: 1rem;
    --form-input-height: 34px;
    --select2-arrow-top: 1px;
}
</style>
</head>
<body class="office central-form">

  <div class='container'>
    <div class='content max420px'>


      <div class="content-form">
        <h4>
            Αποστολή προσκλήσεων
            <span>
              <a type="button" class="btn btn-sm btn-back2panel pull-right" href="/panel">
                Επιστροφή
              </a>
            </span>
        </h4>
        <hr />

        <form method="post">

  <div class='row'>
      <div class='form-group '>
          <label for='members'>Προς *</label>
          
          <select id='members' class='form-select form-select-sm' name='members[]' multiple='multiple' style='width:100%'>
              <?php foreach($teachers as $key => $item) :?>
                  <option value="<?=$item['id']?>"><?=$item['TeacherName']?></option>
              <?php endforeach; ?>
          </select>
      </div>
  </div>

            <br />
            <div class="row">
            <div class='form-group '>
                <button type="submit" class="btn btn-primary btn-sm col-12">
                  Αποστολή προσκλήσεων
                </button>
            </div>
            </div>
            
        </form>
      </div>

      <div class="info clear-read">
        <p>
          Χρησιμοποιήστε τη φόρμα για να προσκαλέσετε νέους χρήστες
          ή για reset ενός account.
        </p>
        <p>
          Κάθε πρόσκληση παραμένει ενεργή για 72 ώρες.
          Στο διάστημα αυτό δεν μπορεί να σταλεί άλλη πρόσκληση στο ίδιο email.
        </p>
        <p>
          Μην στέλνετε πολλές προσκλήσεις ταυτόχρονα.
          Ένας καλός ρυθμός είναι οι 5 προσκλήσεις το λεπτό
          και έως 30 προσκλήσες την ημέρα.
        </p>
      </div>

    </div>
  </div>

</body>

<?php   // include select2 supplementary functions
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/select2-supplementary');   
?>

<script>

$(document).ready(function() {

    // initialize select2 control
    var members = $('#members');
    members.select2({ width: '100%' });
    members.val(null).trigger('change');       


    // When form is submited
    // -------------------------------------------------------------------------

    $('.content form').submit( event => {

        event.preventDefault();


        // prepare data to POST
        var data = {
            ids: getValues($('#members'))
        };
        console.log(data);

        // select action url (add or update)
        var request = '/admin/send/invitation';

        // send POST request
        $.post(request, data)
        .done(function( data ) {
            if (data.status !== false) {
                alert('Έχουν αποσταλεί οι προσλήσεις:\n\n'+ data.sent);
                window.location.href = '/panel';

            } else {
              alert([
                'Κατάσταση αποστολής προσλήσεων:\\n\n',
                data.sent,
                'Υπήρξαν προβλήματα στην αποστολή κάποιων προσκλήσεων.'
              ].join(''));
            }
        });

    });

});
</script>

<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
