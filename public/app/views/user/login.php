<?php 
use anom\core\Render; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Είσοδος Χρήστη</title>
    <meta name="description" content="Πλατφόρμα ηλεκτρονικών αιτήσεων και δημιουργίας διαδικαστικών εγγράφων 1ου Γυμνάσιου Ραφήνας">
    <meta name="keywords" content="Γυμνάσιο Ραφήνας, αιτήσεις, πλάτφόρμα, login">

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

      <div class="info">
        <h4>1o Γυμνάσιο Ραφήνας</h4>
        <p>Πλατφόρμα ηλεκτρονικών αιτήσεων και δημιουργίας διαδικαστικών εγγράφων.</p>
      </div>

      <div class="content-form">
        <h4>Είσοδος</h4>
        <hr />
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control form-control-sm" name="email" aria-label="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Κωδικός πρόσβασης</label>
                <input type="password" class="form-control form-control-sm" name="password" aria-label="Κωδικός πρόσβασης" required>
            </div>
            <br />
            <div class="row justify-content-center"><div class="col-6">
            <button type="submit" class="btn btn-primary btn-sm col-12">Σύνδεση</button>
            </div></div>
        </form>
      </div>

      <div class="info clear-read">
        <!--
        <p>Αν ξεχάσατε τον κωδικό πρόσβασης μπορείτε να δημιουργήσετε ένα νέο (υπο κατασκευή).</p>
        -->
        <p>
          Εαν δικαιούστε αλλά δεν έχετε πρόσβαση στο site, παρακαλούμε επικοινωνήστε
          με τη διεύθυνση του σχολείου προκειμένου να σας αποσταλεί σχετική πρόσκληση.
        </p>
      </div>

    </div>
  </div>

</body>

<script>
    // When form is submited
    // -------------------------------------------------------------------------

    $('.content form').submit( event => {
        event.preventDefault();


        // prepare data to POST
        var data = {
            email: $('input[name=email]').val(),
            password: $('input[name=password]').val()
        };

        // select action url (add or update)
        var request = '/account/check-login';

        // send POST request
        $.post(request, data)
        .done(function( data ) {
            if (data.status !== false) {
                window.location.href = data.status.goto;

            } else {
              alert('Τα στοιχεία πρόσβασης που δώσατε είναι λάθος. Παρακαλώ προσπαθήστε ξανά.');
            }
        });


    });
</script>

<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
