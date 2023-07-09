<?php
$userManager = new UserManager();

$password = $userManager->cryptPassword('102030!!!');

$user = (new User())
    ->setUserName('geo@roptron.gr')
    ->setPassword($password)
    ->setRoles([1]);

$userManager->createUserToken($user);


$algos = [
    [ PASSWORD_DEFAULT, 10 ],
    [ PASSWORD_ARGON2I, 10 ],
    [ CRYPT_BLOWFISH, 10 ],
    [ PASSWORD_DEFAULT, 9 ],
    [ PASSWORD_ARGON2I, 9 ],
    [ CRYPT_BLOWFISH, 9 ],
    [ PASSWORD_DEFAULT, 11 ],
    [ PASSWORD_ARGON2I, 11 ],
    [ CRYPT_BLOWFISH, 11 ],
    [ PASSWORD_DEFAULT, 12 ],
    [ PASSWORD_ARGON2I, 12 ],
    [ CRYPT_BLOWFISH, 12 ],
    [ PASSWORD_DEFAULT, 8 ],
    [ PASSWORD_ARGON2I, 8 ],
    [ CRYPT_BLOWFISH, 8 ],
    [ PASSWORD_DEFAULT, 7 ],
    [ PASSWORD_ARGON2I, 7 ],
    [ CRYPT_BLOWFISH, 7] 
]
?>
<html>
    <head>
        <style>pre{ font: 400 14px/18px 'JetBrains Mono NL', 'Ununtu Mono', Consolas, Monaco; }</style>
    <body>
        <pre>

<?php
foreach($algos as $algo) {
    echo "\n\nalgo= {$algo[0]}, cost={$algo[1]}"
        ."\nhash= ". ( $x = password_hash('102030', $algo[0], ['cost' => $algo[1]]) )
        ."\nbase64=". base64_encode($x);
}

?>

        </pre>
    </body>
</html>
