<?php 

print_r(json_decode( json_encode( [
    'in' => [
        'el' =>  'mesa',
        'en' => 'inside'
    ],
    'out' => 'exo'
])));