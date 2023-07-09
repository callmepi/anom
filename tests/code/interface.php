<?php

interface moving
{
    public function go();
}

class walking implements moving
{
    public function go() { echo 'i walk'; }
}

class driving implements moving
{
    public function go() { echo 'i drive my car'; }
}


class flying implements moving
{
    public function go() { echo 'i got my airplane tickets'; }
}


// $driver = DRIVER;
// $ready = new $driver();

define('DRIVER', 'flying');
$ready = new (DRIVER)();

$ready->go();
