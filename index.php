<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once 'app/services/ioccontainer.php';
use app\services\IoCContainer;

interface IColor {

}

class Color implements IColor {

}

class Red {
    
    private $hex;

    public function __construct(IColor $hex) {
        $this->hex = $hex;
    }

}

IoCContainer::bind('IColor','Color');

var_dump(IoCContainer::make('Red'));

class Counter {

    public $count;

    public function __construct() {
        $this->count = 0;
    }

}

IoCContainer::singleton('SingleCounter', function() {
    $instance = new Counter();
    $instance->count += 1;
    return $instance;
});

var_dump(IoCContainer::make('SingleCounter'));
var_dump(IoCContainer::make('SingleCounter'));