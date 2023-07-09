<?php
echo '<pre>';
echo "\n(in) ". session_id();

echo "\nsesion path: ". session_save_path();

if (isset($_SESSION['c'])) {
    echo "\nexist:". print_r($_SESSION['c'], true);

} else {
    $number = [
        rand(1,1000),
        rand(1,1000),
        rand(1,1000)
    ];
    echo "\ncreated random:". print_r($number, true);
    $_SESSION['c'] = $number;
}

session_regenerate_id();
echo "\n(out) ". session_id();

echo '</pre>';
### class SomeSessionHandler extends SessionHandler
### {
###     private $key;
### 
###     public function __construct()
###     {
###         // nothing
###     }
### 
###     public function close()
###     {
###         return parent::close();
### 
###     }
### 
### }
### 
### // we'll intercept the native 'files' handler, but will equally work
### // with other internal native handlers like 'sqlite', 'memcache' or 'memcached'
### // which are provided by PHP extensions.
### // ini_set('session.save_handler', 'files');
### 
### $handler = new SomeSessionHandler();
### session_set_save_handler($handler, true);
### session_start();
### 
### echo 'hm!';
