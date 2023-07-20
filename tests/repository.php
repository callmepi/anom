<?php

use app\models\market\Market_repository as Market;

// reply_json([
//     'pool' => Market::echo(True)
// ]); die();

$shorts = Market::echo(True);
?><!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>
    <!-- and it's easy to individually load additional languages -->
    <!--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/languages/go.min.js"></script>
    -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/languages/sql.min.js" integrity="sha512-efGcw9G3wD5/VEKACpydwQLvsYs8/QEWGLqnrMp+cEF5jFVdJmbmf3+D+y1LmoQR1IbtzO9XUCTeZhJ1riqX1A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/atom-one-light.min.css" integrity="sha512-o5v54Kh5PH0dgnf9ei0L+vMRsbm5fvIvnR/XkrZZjN4mqdaeH7PW66tumBoQVIaKNVrLCZiBEfHzRY4JJSMK/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/atom-one-dark.min.css" integrity="sha512-Jk4AqjWsdSzSWCSuQTfYRIF84Rq/eV0G2+tu07byYwHcbTGfdmLrHjUSwvzp5HvbiqK4ibmNwdcG49Y5RGYPTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    -->
    <style>
body { font-size: .92em; }
h3, h4, pre, code { font-family: 'JetBrains Mono NL', 'Consolas','Monaco', 'monospace', monospace; }
h3 { padding: 2em 1.5em .5em; }
h4 { padding: .5em 2em; }
    </style>
</head>
<body>
    <?php foreach($shorts as $label => $sql) : ?>
        <?php
            $args = explode('_by:', $label)[1];
            $argsArray = explode('_', $args);
        ?>


        <h3><?=$label?></h3>
        <pre><code class="language-sql">
            <?=$sql?></code></pre>
        <h4>apply:</h4>
        <pre><code class="language-php">
use anom\core\Registrty;
use app\models\market\Market_repository as Market;

// ...

Registry::use('database')->runQuery(
    Market::pull('<?=$label?>'),
    [
<?php foreach($argsArray as $holder) : ?>
        '<?=$holder?>' => ...,
<?php endforeach; ?>
    ]
);
        </code></pre>

    <?php endforeach; ?>

    <script>hljs.highlightAll();</script>
</body>
</html>

