<?php 

use anom\core\Render; 

    if (!isset($administration)) {
        $administration = false;
    }

    if (!isset($no_dataTables)) {
        $no_dataTables = false;
    }

    if (!isset($no_pdfmake)) {
        $no_pdfmake = false;
    }
?>

<!-- FONTS and LIBRARY CSS (self-hosted)
    /////////////////////////////////////////////////////////////////////////-->

    <!-- Fira Sans (self-hosted) -->
<link rel="stylesheet" href="/assets/css/googlefonts/fira-sans-300-400-500.css">

    <!-- fontawesome icons (self-hosted) -->
<link rel="stylesheet" href="/assets/css/fontawesome-6.4.0/css/all.min.css">

    <!-- bootstrap -->
<link rel="stylesheet" href="/assets/css/bootstrap-5.3.0/css/bootstrap.min.css">

<?php if (!$no_dataTables) : ?>
    <!-- datatables css -->
    <link rel="stylesheet" href="/assets/js/DataTables/datatables.min.css">
<?php endif; ?>

    <!-- select2 css -->
<link rel="stylesheet" href="/assets/js/select2-4.1.0-rc0/css/select2.min.css">


<!-- CUSTOM CSS
    /////////////////////////////////////////////////////////////////////////-->

    <!-- main css -->
<link rel="stylesheet" href="/assets/css/class.css">

<!-- THEME overides CSS
    /////////////////////////////////////////////////////////////////////////-->

<?php   // decide a theme
    Render::view('components/theme');
?>

<!-- JAVASCRIPT 
    /////////////////////////////////////////////////////////////////////////-->

    <!-- bootstrap bundle js -->
<script src="/assets/css/bootstrap-5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- jquery -->
<script src="/assets/js/jquery-3.7.0.min.js"></script>

    <!-- basic cookie handling (vanilla js) -->
<script src="/assets/js/cookie.js"></script>

<?php if (!$no_pdfmake) : ?>
    <!-- pdfmake -->
    <script src="<?=SITE_URL?>/assets/js/pdfmake/pdfmake.min.js"></script>
    <script src="<?=SITE_URL?>/assets/js/pdfmake/vfs_fonts.js"></script>
<?php endif; ?>

<?php if (!$no_dataTables) : ?>
    <!-- DataTables js -->
    <script src="/assets/js/DataTables/datatables.min.js"></script>
<?php endif; ?>

    <!-- select2-4.1.0-rc.0 js -->
<script src="/assets/js/select2-4.1.0-rc0/js/select2.min.js"></script>

<?php /* DEPRECATED:
    <!-- marked -->
    <script src="/assets/js/marked/marked.min.js"></script>
    */
?>
<?php /* NOTE:

    <!-- #1
    build your own DataTables ...
    --- -- -- - - -

    https://datatables.net/download/
    -->


    <!-- #2
    date handling in vanilla js ...
    --- -- -- - - -

    https://stackoverflow.com/questions/27267565/javascript-returning-incorrect-date-from-an-input-type-date

    // convert to UTC to avoid "date minus one" issues where the supplied date is interpreted incorrectly as
    // the previous date.

    date = new Date(document.getElementById('date').value);
    console.log("original date: " + date.toString());
    console.log("UTC date string: " + date.toUTCString());

    //produces local machine timezone, and therefore incorrect date value

    date = new Date(date.toUTCString());
    console.log("UTC date string Date constructor: " + date.toString());

    utcDate = new Date(utcString.substr(0, utcString.indexOf("GMT")));
    console.log("UTC date string Date constructor (without 'GMT*'): " + utcDate.toString());
    console.log("UTC date number: " + date.getUTCDate());
    date.setDate(date.getUTCDate());
    date.setMonth(date.getUTCMonth());
    date.setFullYear(date.getUTCFullYear());
    console.log("UTC modified date: " + date.toString());

    Output:

    original date: Fri Jan 29 2021 23:16:11 GMT-0600 (Central Standard Time)
    UTC date string: Sat, 30 Jan 2021 05:16:11 GMT
    UTC date string Date constructor: Fri Jan 29 2021 23:16:11 GMT-0600 (Central Standard Time)
    UTC date string Date constructor (without 'GMT*'): Sat Jan 30 2021 23:16:11 GMT-0600 (Central Standard Time)
    UTC date number: 30
    UTC modified date: Sat Jan 30 2021 23:16:11 GMT-0600 (Central Standard Time)
    -->
*/