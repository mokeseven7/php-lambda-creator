<?php

$rootPath = dirname(dirname(dirname(dirname(__DIR__))));
$rootEnv = $rootPath . '/.env';

$packageRootPath = dirname(__DIR__);
$packageRootEnv = $packageRootPath . '/.env';

if (file_exists($rootEnv)) {
	putenv("LAMBDAROOT={$rootPath}");
	$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
	$dotenv->load();
} else if ($packageRootEnv . '/.env') {
	putenv("LAMBDAROOT={$packageRootPath}");
	$dotenv = Dotenv\Dotenv::createImmutable($packageRootPath);
	$dotenv->load();
} else {
	throw new Exception('Cannot find an .env file');
}
