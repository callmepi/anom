<html>
	<head>
    <title>Dev Tools</title>
	</head>
	<body>
  	<h1>Supported Crypt/Encrypt Methods</h1>

  	<table width="100%">
  	<tr>
  	<td width="50%" valign="top">

  		<h2>CIPHER Methods</h2>
		<pre>	
<?php
$ciphers             = openssl_get_cipher_methods();
$ciphers_and_aliases = openssl_get_cipher_methods(true);
$cipher_aliases      = array_diff($ciphers_and_aliases, $ciphers);

print_r($ciphers);

print_r($cipher_aliases);

?>
		</pre>
	</td>

  	<td width="50%" valign="top">

  		<h2>DIGER Methods</h2>
		<pre>	
<?php
$digests             = openssl_get_md_methods();
$digests_and_aliases = openssl_get_md_methods(true);
$digests_aliases     = array_diff($digests_and_aliases, $digests);

print_r($digests);

print_r($digests_aliases);
?>

		</pre>

  		<h2>HASH Algorythms</h2>
		<pre>
			<?php print_r( hash_algos() ); ?>
		</pre>

        <h2>Random</h2>
        <pre>
random_bytes()
32Bytes: <?=bin2hex(random_bytes(32))?>
        </pre>


	</td>

	</tr>
	</table>

	</body>
</html>
