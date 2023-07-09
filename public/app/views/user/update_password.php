<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Αλλαγή Password</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes',
          [ 'no_dataTables' => true, 'no_pdfmake' => true ]
        );
    ?>
</head>
<body class="office central-form">

  <div class='container'>
    <div class="content max420px">

      <div class='content-form'>
          <form method="post">
              <div>
                <h4>
                    Αλλαγή Password
                    <button class="btn btn-back2panel btn-sm">
                        Επιστροφή
                    </button>
                </h4>
                <hr/>

                <?=$form['html']?>

                <br />
                <button type="submit" class="btn btn-primary btn-sm col-12">Ενημέρωση</button>

              </div>
          </form>
      </div>

    </div>
  </div>

</body>

<script>// initialize needed global variables
    var values = {};
    var empty = '';
</script>

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
    Render::view('js/submit/update-password');
?>

<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
