<?php
/**
 * Password Hash Cost Calculator
 *
 * Set the ideal time that you want a password_hash() call to take and this
 * script will keep testing until it finds the ideal cost value and let you
 * know what to set it to when it has finished
 * 
 * credit: https://gist.github.com/Antnee/a072b7a3c59334bf1872
 */

// Milliseconds that a hash should take (ideally)
$mSec = 100;

$password = 'MyT3ST_P4$$w0rD';

echo '<pre>';
echo "\nPassword Hash Cost Calculator\n\n";
echo "Testing BCRYPT hashing the password '$password'\n\n";
echo "We're going to run until the time to generate the hash takes longer than {$mSec}ms\n";

$cost = 3;
do {
    $cost++;
    echo "\nTesting cost value of $cost: ";
    $time = benchmark($password, $cost);
    echo "... took $time";
} while ($time < ($mSec/1000));

echo "\n\nIdeal cost is $cost\n";
echo "\nRunning 100 times to check the average:\n";

$start = microtime(true);
$times = [];
for ($i=1;$i<=100;$i++) {
    echo "\r$i/100";
    $times[] = benchmark($password, $cost);
}

echo "\n\ndone benchmarking in ".(microtime(true)-$start)."\n";

echo "\nSlowest time: ".max($times);
echo "\nFastest time: ".min($times);
echo "\nAverage time: ".(array_sum($times)/count($times));

echo "\n\nFinished\n";
echo "</pre>";

function benchmark($password, $cost=4)
{
    $start = microtime(true);
    password_hash($password, PASSWORD_BCRYPT, ['cost'=>$cost]);
    return microtime(true) - $start;
}
