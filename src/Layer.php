<?php

use Popcorn\PHPLambda;

use Aws\Lambda\LambdaClient;

class Layer
{
	public function __construct()
	{ }
}

// $result = $client->publishLayerVersion([
//     'CompatibleRuntimes' => ['<string>', ...],
//     'Content' => [ // REQUIRED
//         'S3Bucket' => '<string>',
//         'S3Key' => '<string>',
//         'S3ObjectVersion' => '<string>',
//         'ZipFile' => <string || resource || Psr\Http\Message\StreamInterface>,
//     ],
//     'Description' => '<string>',
//     'LayerName' => '<string>', // REQUIRED
//     'LicenseInfo' => '<string>',
// ]);
