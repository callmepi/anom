<?php
echo "test started... ";
ob_flush();

Benchmark::add_spot('query-10000-products');
$db = Registry::use('database');
$products = $db->runQuery("SELECT * FROM products LIMIT 10000",[]);

Benchmark::add_spot('serialize-start');
foreach($products as $product) {
    $ser = serialize($product);
}

Benchmark::add_spot('serialize+unserialize-start');
foreach($products as $product) {
    $ser = serialize($product);
    $ori = unserialize($ser);
}

Benchmark::add_spot('jsonencode-start');
foreach($products as $product) {
    $json = json_encode($product);
}

Benchmark::add_spot('jsonencode+decode-start');
foreach($products as $product) {
    $json = json_encode($product);
    $ori = json_decode($json);
}

Benchmark::add_spot('end');
echo(Benchmark::report(BENCH_REPORT_COMMENT));
echo "test completed!";

die();
