<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Αίτηση</title>

    <?php   // header includes
        ////////////////////////////////////////////////////////////////////////
        Render::view('components/header_includes', [ 'no_dataTables' => true ]);
    ?>

</head>
<body class="office central-form">

  <div class='container'>
    <div class="content max720px">

      <div class='content-form'>
          <form method="post">
              <div>

                <h4>
                    Αίτηση
                    <button class="btn btn-back2panel btn-sm">
                        Επιστροφή
                    </button>
                </h4>
                <hr />

                <?=$form['html']?>

                <br />
                <div class="row">
                    <div class="col-4">
                        <button class="btn btn-preview btn-sm col-12">
                            Προεπισκόπηση
                        </button>
                    </div>
                    <div class="col-8">
                        <button type="submit" class="btn btn-primary btn-sm col-12">
                            Καταχώριση
                        </button>
                    </div>
                </div>

              </div>
          </form>
      </div>

      <!-- attachments -->
    <?php /* exclude until beta version
      <div class="info">
        <div class="row form-group">

            <label class="control-label col-sm-12" for="media">Συνημμένα</label>

            <div class="col-sm-12 post-media">

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-sm btn-success pull-right over-bar"
                    data-bs-toggle="modal" data-bs-target="#editorModal" data-action="upload_file">
                    <i class="fas fa-plus"></i> &nbsp; Προσθήκη
                </button>

                <ul id="files_list">
                </ul>

            </div>

        </div>
      </div>
      */
    ?>  
    </div>
  </div>

  <!-- MODAL for file uploads and preview  -->
  <div class="modal fade" id="editorModal" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
                <!-- content will be dynamic -->
        </div>
    </div>
  </div>

</body>

<!-- SCRIPTS 
////////////////////////////////////////////////////////////////////////////////
-->

<script>// initialize needed global variables
    var ref = {
        organization: '<?=DEFAULT_SCHOOL?>',
        applier: '<?=$applier?>',
        ticket: '<?=$ticket?>'
    };

</script>

<?php   // include select2 supplementary functions
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/select2-supplementary');   
?>

<script>// pass dynamic js prepared by form designer
    $(document).ready(function() {

        // intializations from form-definition
        ////////////////////////////////////////////////////////////////////////
        <?=$form['init_js']?>
       
    });
</script>

<?php   // handle form submition
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/submit/application-form');
?>

<?php   // load application pdf-designer
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/pdf-designer/application');
?>

<?php   // include attachments handling
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/attachments-handling');   
?>

<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
