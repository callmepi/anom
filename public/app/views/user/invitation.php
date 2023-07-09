<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Εγγραφή</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes', [ 'no_dataTables' => true ]);
    ?>
</head>
<body class="office central-form">

  <div class='container'>
    <div class="content max540px">

      <div class="info">
          <h4>1o Γυμνάσιο Ραφήνας</h4>
          <p>
            Πλατφόρμα ηλεκτρονικών αιτήσεων
            και δημιουργίας διαδικαστικών εγγράφων.
          </p>
      </div>

      <div class='content-form'>
          <form method="post">
              <div>
                <h4>Πρόσκληση #<?=$invitation_number?></h4>
                <hr/>

                <?=$form['html']?>

                <br />
                <button type="submit" class="btn btn-primary btn-sm col-12">Ενεργοποίηση Λογαριασμού</button>

              </div>
          </form>
      </div>

      <div class="info clear-read">
        <p>
          Απαιτείται η συμπλήρωση όλων των στοιχείων
          προκειμένου να γίνεται γρήγορα η σύνταξη των εγγράφων.
        </p>
        <p>
          Αν έχετε ενεργοποιήσει ήδη το λογαρισμό σας
          <a href="/login">μπορείτε να συνδεθείτε</a>.
        </p>
        <p>
          Τα στοιχεία σας δεν πρόκειται να δημοσιοποιηθούν και προστατεύονται
          κατά την μεταφορά τους στον server με ισχυρή κρυπτογράφιση.
          Η ασφάλεια της σύνδεσης με το site πιστοποιείται από την
          <a href="https://letsencrypt.org/" target="_blanc">Let's Encrypt</a>.
        </p>
      </div>

    </div>
  </div>

</body>

<script>// initialize needed global variables
    var values = {};
    var empty = '';
</script>

<?php   // include select2 supplementary functions
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/select2-supplementary');   
?>

<script>
$(document).ready(function() {

    // intializations from form-definition
    ////////////////////////////////////////////////////////////////////////////

    <?=$form['init_js']?>


    // set known values
    ////////////////////////////////////////////////////////////////////////////

    <?=$form['values_js']?>

});
</script>

<?php   // handle form submition
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/submit/invitation-form');
?>

<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
