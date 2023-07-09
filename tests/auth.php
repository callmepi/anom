<?php

class Pass_test
{
    use PasswordTrait;


    public function isTextPasswordValid($plain, $hash): bool
    {
        return password_verify($plain, $hash);
    }
}


$pass = new Pass_test();

$password = [
    '102030!!!',
    '1234',
    'password'
];


$hash = [];
$verify = [];


// create pass hashes
foreach($password as $val)
{
    $x = $pass->cryptPassword($val);
    $hash[] = $x;
}
?>
<html>
	<head>
    <title>Dev Tools</title>
	</head>
	<body>
  		<h2>crypt passwords; then...</h2>
        <h2>check password validation</h2>
<pre>
<?php


for($i=0 ; $i < 3 ; $i++) {
    echo $i ."> ". $password[$i] ." -> ". $hash[$i]. "\n";
    $verify[$i] = ($pass->isTextPasswordValid($password[$i], $hash[$i])) ? 'valid' : 'not-match';
}

?>
</pre>
        <?php print_r( $hash ); ?>

        <?php print_r( $verify ); ?>
		</pre>
    </body>
</html>