<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=SITE_TITLE?></title>

    <style>
        html,body{
        padding:0; margin:0;
        font-size:1.15em; font-family: Arial, Helvetica, Ubuntu, sans-serif;
        color: #037; background: #eee;
        line-height:1.2em; 
        }
        h3 {
        font-size: 2em; font-style: normal;
        font-weight: 400;
        color: #05a;
        margin: 0; padding: 5px;
        opacity: 0.27;
        border-bottom: 1px solid #05a;
        }
        .container { display: flex; height: 100vh; align-items:center; }
        .content { position: relative;
            max-width:320px; width: 100%;
            margin: auto auto; padding: 1.5em 1.5em 3em 1.5em;
            text-align: center; justify-center: center;
            opacity: .5;
        }
        .btn { font-size: 18px; padding: 4px 12px; }
    </style>

    <script src="<?=SITE_URL?>/assets/js/pdfmake/pdfmake.min.js"></script>
    <script src="<?=SITE_URL?>/assets/js/pdfmake/vfs_fonts.js"></script>

    <?php   // load pdf-designer
        ////////////////////////////////////////////////////////////////////////
        Render::view('js/pdf-designer/'. $petition['type']);
    ?>
</head>
<body>
    <div class='container'>
      <div class='content'>

          <!--<h3>Παρακαλώ περιμένετ</h3>-->
          Η διαδικασία λήψης του εγγράφου είναι σε εξέλιξη...<br />
         
      </div>
    </div>
</body>
<script>
var data = <?=json_encode($petition, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)?>;

pdfMake.fonts = {
        FiraSans: {
            normal: '<?=SITE_URL?>/assets/fonts/FiraSans-Light.ttf',
            bold: '<?=SITE_URL?>/assets/fonts/FiraSans-Medium.ttf'
            // italics: 'https://example.com/fonts/fontFile3.ttf',
            // bolditalics: 'https://example.com/fonts/fontFile4.ttf'
        },
        FiraMono: {
            normal: '<?=SITE_URL?>/assets/fonts/FiraMono-Regular.ttf'
        }
    }

pdfMake.createPdf(
    designer( data.form_structure, data.form_structure.ref )
).download('<?=$petition['signature']?>.pdf');
</script>
</html>