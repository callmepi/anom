<?php
/** general error page
 * 
 * defaults to 404 error but can be used for any other error code
 * 
 * imported variables:
 * 
 * optional @var $error_code (int) : error code
 * optional @var $moto (string) : super-mini description text
 * 
 */

  if (!isset($error_code)) {     // if error code not set
      $error_code = 404;        // set default (404)
  }
  
  http_response_code($error_code);
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title><?=$error_code?> : <?= $moto ?? 'Not found' ?></title>

    <style>
    html,body{
      padding:0; margin:0;
      font-size:1.25em; font-family: Arial, Helvetica, Ubuntu, sans-serif;
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
        opacity: .33;
    }
    .btn { font-size: 18px; padding: 4px 12px; }
    </style>
  </head>
  <body>
    <div class='container'>
      <div class='content'>

          <h3><?=$error_code?></h3>
          <?= $moto ?? 'Not found' ?><br />
         
      </div>
    </div>
  </body>
</html>
