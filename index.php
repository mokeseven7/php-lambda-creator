<?php

require "./vendor/autoload.php";

//use Popcorn\PHPLambda\ZipFactory;
use Popcorn\PHPLambda\Lambda;

$lambda = new Lambda('php-lambda-builder');
$lambda->createOrUpdate('php-lambda-builder');
