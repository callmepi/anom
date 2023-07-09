<?php

echo "test started... ";
ob_flush();

Benchmark::add_spot('query-10000-products');
$db = Registry::use('database');
$products = $db->runQuery("SELECT * FROM products LIMIT 10000",[]);



$originalLength = $compressLength = 0;
Benchmark::add_spot('gzcompress(1)-10000-products');
foreach($products as $product) {
    $original = serialize($product);
    $compress = gzcompress($original, 1);
    $originalLength = strlen($original);
    $compressLength = strlen($compress);
}
echo "<pre>gzcompress(1)
compressed {$originalLength} to {$compressLength} or ";
echo ($originalLength-$compressLength)*100/$originalLength ."%\n</n>";



$originalLength = $compressLength = 0;
Benchmark::add_spot('gzcompress(4)-10000-products');
foreach($products as $product) {
    $original = serialize($product);
    $compress = gzcompress($original, 4);
    $originalLength = strlen($original);
    $compressLength = strlen($compress);
}
echo "<pre>gzcompress(4)
compressed {$originalLength} to {$compressLength} or ";
echo ($originalLength-$compressLength)*100/$originalLength ."%\n</n>";



$originalLength = $compressLength = 0;
Benchmark::add_spot('gzcompress(6)-10000-products');
foreach($products as $product) {
    $original = serialize($product);
    $compress = gzcompress($original, 6);
    $originalLength = strlen($original);
    $compressLength = strlen($compress);
}
echo "<pre>gzcompress(6)
compressed {$originalLength} to {$compressLength} or ";
echo ($originalLength-$compressLength)*100/$originalLength ."%\n</n>";



$cache = Registry::use('cache');

Benchmark::add_spot('Cache-save-10000-products');
foreach($products as $product) {
    $cache->set('cache.sav.'.$product['ID'],  serialize($product), 3600);   
}



Benchmark::add_spot('gz(ser(),6)+Save-10000-products');
foreach($products as $product) {
    $cache->set('zx.ser.sav.'.$product['ID'],  gzcompress(serialize($product), 6), 3600);   
}

$maxLen = 0;
Benchmark::add_spot('sha1(serialize())-10000-products');
foreach($products as $product) {
    $original = sha1(serialize($product));
    $maxlen = $maxLen > strlen($original) ? $maxLen : strlen($original);
}


Benchmark::add_spot('end');
echo(Benchmark::report(BENCH_REPORT_COMMENT));

echo "test completed!";
die();
