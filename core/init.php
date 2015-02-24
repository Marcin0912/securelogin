<?php

// PHP OOP Login/Register System: Initialization (Part 4/23)
session_start();

$GLOBALS['config'] = array (
    'mysql' => array(
        'host'      => '127.0.0.1',
        'username'  => 'marcin',
        'password'  => 'marnica)(@@!!',
        'db'        => 'lr'
    ),
    'remember' => array(
        'cookie_name'   => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name'  => 'user',
        'token_name'    => 'token'
    )
);

// This function standard php library autoload register
// that takes anonymus function with a param $class,
// $db = new DB(); will call this function with DB as the argument for $class
// $user = DB::getInstance() same here DB gets passed to anonymus function as an argument
// so the call looks like spl_autoload_register(require_once 'classes/DB.php');
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if($hashCheck->count()) {
        echo $hashCheck->first()->user_id;
        $user = new User($hashCheck->first()->user_id);

    }
}
