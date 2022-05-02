<?php
$base = "../";

include($base."code/core/bootstrap.php");

$request = new Request();

$request->page = "test";
$request->base = $base;

print_r(SingletonRegistry::$registry['PartyService']->get(2));