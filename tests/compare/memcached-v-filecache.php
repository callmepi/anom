<?php 
echo "test started... ";
ob_flush();

Benchmark::add_spot('query-10000-products');
$db = Registry::use('database');
$products = $db->runQuery("SELECT * FROM products LIMIT 10000",[]);

Benchmark::add_spot('memcached-set-start');
foreach($products as $product) {
    MemcachedCache::set('prod'.$product['ID'], $product, 600);
}

Benchmark::add_spot('memcached-get-start');
foreach($products as $product) {
    $x = MemcachedCache::get('prod'.$product['ID']);
}

Benchmark::add_spot('filecache-set-start');
foreach($products as $product) {
    FileCache::set('prod'.$product['ID'], $product, 600);
}

Benchmark::add_spot('filecache-get-start');
foreach($products as $product) {
    $x = FileCache::get('prod'.$product['ID']);
}

Benchmark::add_spot('end');
echo(Benchmark::report(BENCH_REPORT_COMMENT));
echo "test completed!";

die();   

