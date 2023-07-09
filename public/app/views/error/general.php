<?php 
/** general message template
 * -----------------------------------------------------------------------------
 * renders an error with custom title and message
 *  
 * passed values:
 * 
 * optional @var $title (string): title
 * optional @var $message (string): message
 * 
 * NOTE:
 * if title and/or message are not passed, then defaults are used
 * 
 * -----------------------------------------------------------------------------
 */

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width initial-scale=1">
    <title>
      <?= isset($title)
          ? (SITE_TITLE .": ". $title)
          : PAGE_404_TITLE
      ?>
    </title>

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
        max-width: 480px; width: 100%;
        margin: auto auto; padding: 1.5em 1.5em 3em 1.5em;
        text-align: center; justify-center: center;
        opacity: .33;
        font-size: .87em
    }
    .btn { font-size: 18px; padding: 4px 12px; }
    </style>
  </head>
  <body>
    <div class='container'>
      <div class='content'>
          <h3><?= isset($title) ? $title : PAGE_404_TITLE ?></h3>
          <br />
          <br />
          <span><?= $message ?? "" ?></span>
      </div>
    </div>
  </body>
</html>
