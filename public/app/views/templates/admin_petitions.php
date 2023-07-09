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

        <div class="col-sm-12">

<div class="manage">
    <!-- title bar -->
    <div class="title-bar">
        <div class="row">
            <h5 class="col-md-10">Διαχείριση Αιτήσεων και Δοικητικών Εγγράφων</h5>
            <div class="col-md-2">
                <a type="button" class="btn btn-sm btn-back2panel pull-right" href="/panel">
                    Επιστροφή
                </a>
            </div>
        </div>
        
    </div>


    <div class="row">
        <div class="col-md-12">

            <div id="petitions">

                <table id="dt-petitions" class="table table-responsive dt-table" style="width:100%">
                    <thead>
                        <!--<th class="dt-id">α/α</th>-->
                        <th class="dt-subject">Θέμα</th>
                        <th class="dt-protocol">Αρ.Πρωτόκολλου</th>
                        <th class="dt-user">Χρήστης</th>
                        <!-- <th class="dt-signature">Signature</th> -->
                        <th class="dt-datepost">Ημερ. Καταχώρισης</th>
                        <th class="dt-actions"></th>
                    </thead>
                </table>

            </div>

        </div>
    </div>
    
</div><!-- /manage -->

        </div>

    </div>
  </div>




  <!-- MODAL for set protocol number -->
  <div class="modal fade" id="managePetition" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
          
          <form method="post" action="">

              <div class="modal-header">
                  <h4 class="modal-title" id="myModalLabel">Ενημέρωση αριθμού πρωτόκολλου</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div><!-- /modal-header -->

              <!-- modal-body -->
              <div class="modal-body">

                  <div class="row form-group">
                      <label class="control-label col-sm-12" for="protocol">Αριθμός Πρψτοκόλλου</label>
                      <div class="col-sm-12">
                          <input class="form-control form-control-sm" type="text" name="protocol" value="" required="">
                      </div>
                  </div>

                  <div class="row form-group"><!-- hiddens -->
                      <input type="hidden" name="id" value="0"><!-- petition-id -->
                      <input type="hidden" name="signature" value=""><!-- signature -->
                  </div>
                          
              </div><!-- /modal body -->

              <div class="modal-footer">
                  <div class="row form-actions" style="width: 50%;">

                        <div class="col-sm-4">
                            <button type="button" class="btn btn-default js-modal-close col-12" data-bs-dismiss="modal">Κλείσιμο</button>
                        </div>
                        <div class="col-sm-8">
                            <button class="btn btn-primary col-12" type="submit">Καταχώριση</button>
                        </div>

                  </div>
              </div><!-- /modal-footer -->

          </form><!-- /form -->

          </div>
      </div>
  </div>

  </body>

<script src="/assets/js/panel/admin_petitions.js"></script>

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
