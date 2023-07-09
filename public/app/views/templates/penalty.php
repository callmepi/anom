<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?> - Πρακτικό Πειθαρχικής Υπόθεσης</title>

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
                    Πρακτικό Πειθαρχικής Υπόθεσης
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

    </div>
  </div>

</body>

<!-- SCRIPTS 
////////////////////////////////////////////////////////////////////////////////
-->

<script>
    var ref = {
        organization: '<?=DEFAULT_SCHOOL?>',
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
    Render::view('js/submit/penalty-form');
?>


<?php   // load application pdf-designer
    ////////////////////////////////////////////////////////////////////////////
    Render::view('js/pdf-designer/penalty');
?>



    
<!-- DEPRECATED:
<script>
    $(document).ready(function() {

    // When form is submited
    ////////////////////////////////////////////////////////////////////////////

    $('.content-form form').submit( event => {
        event.preventDefault();

        if (false) {
          alert('Τα δυο passrods δεν ταιριάζουν!');

        } else {

            var data = {        // select content data
                of_article: selected_text($('select[name=of_article]')),
                student_names: $('textarea[name=student_names]').val(),
                of_department: $('input[name=of_department]').val(),
                date: $('input[name=date]').val(),
                time: $('input[name=time]').val(),
                place: selected_text($('select[name=place]')),
                rapporteur: selected_text($('select[name=rapporteur]')),
                charge: $('textarea[name=charge]').val(),
                apology: $('textarea[name=apology]').val(),
                decision_reasoning: $('textarea[name=decision_reasoning]').val(),
                decision: selected_text($('select[name=decision]')),
                decision_more: $('input[name=decision_more]').val(),
                president: selected_text($('select[name=president]')),
                members: selected_multitexts($('select[name="members[]"]'))
            };

            var form_data = {     // prepare data to post
              subject: $('input[name=of_department]').val(),
              content: JSON.stringify(data),
              on_date: $('input[name=date]').val(),
              user_id: $('input[name=id]').val()
            }

            // select action url (add or update)
            // var request = '/account/register';
            console.log(data);

            // send POST request
            // $.post(request, form_data)
            // .done(function( data ) {
            //     $('.classroom .content-form').html(data.message)
            // });
        }  

    });


  });
</script>
-->
<?php // credits
  //////////////////////////////////////////////////////////////////////////////
  Render::view('js/credits');
?>
</html>
