<?php

$rootPath = dirname(dirname(dirname(__DIR__)));
$rootEnv = $rootPath . '/.env';

$packageRootPath = dirname(__DIR__);
$packageRootEnv = $packageRootPath . '/.env';

if (file_exists($rootEnv)) {
	echo "YES1 \n\n";
	print_r($rootEnv);
	echo "\n\n";
	$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
	$dotenv->load();
} else if ($packageRootEnv . '/.env') {
	echo "YES2 \n\n";
	$dotenv = Dotenv\Dotenv::createImmutable($packageRootPath);
	$dotenv->load();
} else {
	echo "YES3 \n\n";
}
