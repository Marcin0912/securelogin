<?php
// PHP OOP Login/Register System: Logging out (Part 18/23)
require_once 'core/init.php';

$user = new User();
$user->logout();

Redirect::to('index.php');