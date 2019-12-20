<?php

require "./vendor/autoload.php";

//use Popcorn\PHPLambda\ZipFactory;
use Popcorn\PHPLambda\Lambda;

$lambda = new Lambda();
$lambda->createFunction('php-lambda-builder');
