<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Control Panel</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes');
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
    <div class='content row'>

        <div class="col-2 info">
            <?php   // menu
            ////////////////////////////////////////////////////////////////////
            Render::view('components/menu');
            ?>
        </div>

        <div class="col-10">
            <!-- table -->
        </div>

    </div>
  </div>

</body>

</html>
