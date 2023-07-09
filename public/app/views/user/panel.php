<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Control Panel</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes', [ 'no_pdfmake' => true ]);
    ?>
<style>
:root {   /* login-form overides */
    --form-content-font-size: 1rem;
    --form-input-height: 34px;
    --select2-arrow-top: 1px;
}
</style>
</head>
<body class="office central-form left-menu">

  <div class='container'>
    <div class='content row'>

        <div class="col-xs-12 col-sm-3 info">
            <?php   // menu
              //////////////////////////////////////////////////////////////////
              Render::view('components/menu', ['is_admin' => $is_admin]);
            ?>
        </div>

        <div class="col-xs-12 col-sm-9">

            <?php   // content sub-section
              //////////////////////////////////////////////////////////////////
              Render::view('components/'.$content, [
                'is_admin' => $is_admin,
                'user' => $user
              ]);
            ?>

        </div>

    </div>
  </div>

</body>

<script src="/assets/js/panel/<?=$content?>.js"></script>

  <!-- scripts 
    * handle show petition request

    if admin:
    * modal + give protocol-number
  -->
<?php // credits
    //////////////////////////////////////////////////////////////////////////////
    Render::view('js/credits');
?>
</html>
