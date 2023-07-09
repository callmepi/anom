<?php 

define('NORMAL',   "\033[39m");   // white
define('SUCCESS',  "\033[32m");   // green
define('FAIL',   "\033[1;31m");   // red
define('ERROR',  "\033[1;31m");   // red
define('PROGRESS', "\033[33m");   // orenge

define('WHITE',   "\033[39m");    // white
define('GREEN',   "\033[32m");    // green
define('RED',   "\033[1;31m");    // red
define('ORANGE',  "\033[33m");    // orenge


function textColor($text, $mode = NORMAL) {
    switch ($mode) {
        case SUCCESS:
        case GREEN:
            return SUCCESS.$text.NORMAL;
            break;

        case FAIL:
        case RED:
            return FAIL.$text.NORMAL;
            break;

        case PROGRESS:
        case ORANGE:
            return PROGRESS.$text.NORMAL;
            break;

        default:
            return NORMAL.$text;
    }
}


// force printing string with specified length
function textWidth($str, $len = 72) {
    $space = " ";
    for($i = 0 ; $i<$len ; $i++) $space .= ".";

    return mb_substr($str.$space, 0, $len-1) ." ";
}