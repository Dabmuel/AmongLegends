<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors','off');
$base = "../";

include($base."code/core/bootstrap.php");

$request = new Request();

$request->page = "index";
$request->base = $base;

SingletonRegistry::$registry["Router"]->process();
