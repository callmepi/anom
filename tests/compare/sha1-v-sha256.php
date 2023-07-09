<?php 

echo "test started... ";
echo '<pre>';
echo "\nsha1: ". sha1('hello');
echo "\nsha256: ". hash('sha256', 'hello');
echo '</pre>';
ob_flush();

Benchmark::add_spot('100.000 x sha1');
for($i = 0 ; $i<100000 ; $i++) $x = sha1($i);


Benchmark::add_spot('100.000 x sha256');
for($i = 0 ; $i<100000 ; $i++) $x = hash('sha256', $i);

Benchmark::add_spot('end');

echo (Benchmark::report(BENCH_REPORT_COMMENT));

echo "test completed!";
die();
    