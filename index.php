<?php

require "./vendor/autoload.php";

use Popcorn\PHPLambda\ZipFactory;
use Popcorn\PHPLambda\Lambda;

$lambda = new Lambda();

print_r($lambda->getClient());
