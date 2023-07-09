<?php 

echo "test started... ";
ob_flush();

Benchmark::add_spot('100.000 x md5');
for($i = 0 ; $i<100000 ; $i++) $x = md5($i);


Benchmark::add_spot('100.000 x sha');
for($i = 0 ; $i<100000 ; $i++) $x = sha1($i);

Benchmark::add_spot('end');
echo(Benchmark::report(BENCH_REPORT_COMMENT));
echo "test completed!";

die();
    