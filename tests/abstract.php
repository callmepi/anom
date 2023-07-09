<?php

abstract class abs {

  public static $abs = [
    'one' => 10,
    'to' => [20, 220],
    'tree' => [30, [330, 3330]]
  ];
    
  public static function foo($who) {
    print_r(static::$abs[$who]);
  }

}

class Xtra extends abs {

  public static $abs = [
    'Oh' => 'Yoo hoo!',
    'my' => ['babe', 'be', 'mine']
  ];

  // overide method
  public static function foo($who) {
    print_r(['overiding', $who, 'my']);
  }
  
}

class Repo extends abs {
  public static $abs = [
    'one' => 1,
    'to' => [2, 22],
    'tree' => [3, [33, 333]]
  ];
}

class Neva extends abs {
  // use abstract's (default) array
}

Xtra::foo('Oh');
Repo::foo('to');
Neva::foo('tree');

