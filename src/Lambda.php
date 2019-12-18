<?php

namespace Popcorn\PHPLambda;

use Aws\Credentials\Credentials;
use Aws\Lambda\LambdaClient;
use Exception;

class Lambda
{
	private $config = [];
	protected $lambda;

	public function __construct(array $config = [])
	{
		$this->config['region'] = isset($config['region']) ? $config['region'] : getenv("AWS_REGION");
		$this->config['version'] = getenv("AWS_LAMBDA_CLIENT_VERSION") ? getenv("AWS_LAMBDA_CLIENT_VERSION")  : 'latest';
		$this->config['credentials'] =  new Credentials(getenv("AWS_ACCESS_KEY_ID"), getenv("AWS_SECRET_ACCESS_KEY"));

		$this->lambda = new LambdaClient($this->config);
	}

	public function createFunction($name)
	{
		$buildZip = new ZipFactory('function', ['outputDir' => 'function-build']);
		$buildZip->zip('function-build.zip');

		$params = $this->functionConfiguration($name);
		try {
			$this->lambda->createFunction($params);
		} catch (Exception $exception) {
			echo "<pre>";
			print_r($exception->getMessage());
		}
	}

	public function functionConfiguration($name)
	{
		return [
			'Code' => [
				// 'S3Bucket' => 'phplayer-rally-mmcgrath',
				// 'S3Key' => 'function-build.zip',
				'ZipFile' => file_get_contents('function-build.zip'),
			],
			'Layers' => [
				getenv("AWS_RUNTIME_LAYER_ARN"),
				getenv("AWS_VENDOR_LAYER_ARN"),
			],
			'FunctionName' => $name,
			'Handler' => 'hello',
			'Role' => 'arn:aws:iam::869029932727:role/api-lambda',
			'Runtime' => 'provided',

		];
	}
}


// $result = $client->createFunction([
//     'Code' => [ // REQUIRED
//         'S3Bucket' => '<string>',
//         'S3Key' => '<string>',
//         'S3ObjectVersion' => '<string>',
//         'ZipFile' => <string || resource || Psr\Http\Message\StreamInterface>,
//     ],
//     'DeadLetterConfig' => [
//         'TargetArn' => '<string>',
//     ],
//     'Description' => '<string>',
//     'Environment' => [
//         'Variables' => ['<string>', ...],
//     ],
//     'FunctionName' => '<string>', // REQUIRED
//     'Handler' => '<string>', // REQUIRED
//     'KMSKeyArn' => '<string>',
//     'Layers' => ['<string>', ...],
//     'MemorySize' => <integer>,
//     'Publish' => true || false,
//     'Role' => '<string>', // REQUIRED
//     'Runtime' => 'nodejs|nodejs4.3|nodejs6.10|nodejs8.10|nodejs10.x|nodejs12.x|java8|java11|python2.7|python3.6|python3.7|python3.8|dotnetcore1.0|dotnetcore2.0|dotnetcore2.1|nodejs4.3-edge|go1.x|ruby2.5|provided', // REQUIRED
//     'Tags' => ['<string>', ...],
//     'Timeout' => <integer>,
//     'TracingConfig' => [
//         'Mode' => 'Active|PassThrough',
//     ],
//     'VpcConfig' => [
//         'SecurityGroupIds' => ['<string>', ...],
//         'SubnetIds' => ['<string>', ...],
//     ],
// ]);
