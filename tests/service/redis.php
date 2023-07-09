<?php

$data = [
    'test' => ['Redis', 'is', 'working'],
    'redis' => '!!'
];

try {

    $redis = new \Predis\Client([
        'host' => 'redis'
    ]);

    foreach($data as $key => $value) {
        echo $key .' ';
        $redis->set( $key, serialize($value), 'EX', 60 );
    }
    echo "...\n\n";

} catch (Exception $e) { 
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$test = unserialize($redis->get('test'));
$redis = unserialize($redis->get('redis'));

echo implode(' ', $test) . $redis;