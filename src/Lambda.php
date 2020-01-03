<?php

namespace Popcorn\PHPLambda;

use Aws\Credentials\Credentials;
use Aws\Lambda\LambdaClient;
use Popcorn\PHPLambda\ZipFactory;
use Exception;

class Lambda
{
	private $config = [];
	protected $lambda;
	protected $name;

	public function __construct($name = null, array $config = [])
	{
		$this->config['region'] = isset($config['region']) ? $config['region'] : getenv("AWS_REGION");
		$this->config['version'] = getenv("AWS_LAMBDA_CLIENT_VERSION") ? getenv("AWS_LAMBDA_CLIENT_VERSION")  : 'latest';
		$this->config['credentials'] =  new Credentials(getenv("AWS_ACCESS_KEY_ID"), getenv("AWS_SECRET_ACCESS_KEY"));
		$this->name = $name;

		$this->lambda = new LambdaClient($this->config);
	}

	public function createFunction($name)
	{
		//Left off here
		$buildZip = new ZipFactory('function', ['outputDir' => 'src']);
		$buildZip->zip('src.zip');

		$params = $this->functionConfiguration($name);
		try {
			$this->lambda->createFunction($params);
		} catch (Exception $exception) {
			print_r($exception->getMessage());
		}
	}

	public function updateLambdaCode($name)
	{
		$buildZip = new ZipFactory('function', ['outputDir' => 'src']);
		$buildZip->zip('src.zip');

		$updated = $this->lambda->updateFunctionCode([
			'FunctionName' => $name,
			'Publish' => true,
			'ZipFile' => file_get_contents('src.zip'),
		]);

		echo $updated['FuntionName'] . ' Has Been Updated!';
	}

	public function createOrUpdate()
	{
		$functions = $this->lambda->listFunctions();
		$list = [];

		foreach ($functions['Functions'] as $function) {
			array_push($list, $function['FunctionName']);
		}

		if (in_array($this->name, $list)) {
			//the function already exists in lambda, update it
			$this->updateLambdaCode($this->name);
		} else {
			//the function does not exist in lambda, create it
			$this->createFunction($this->name);
		}
	}

	public function functionConfiguration($name)
	{
		return [
			'Code' => [
				// 'S3Bucket' => 'phplayer-rally-mmcgrath',
				// 'S3Key' => 'function-build.zip',
				'ZipFile' => file_get_contents("src.zip"),
			],
			'Layers' => [
				getenv("AWS_RUNTIME_LAYER_ARN"),
				getenv("AWS_VENDOR_LAYER_ARN"),
			],
			'FunctionName' => $name,
			'Handler' => getenv('AWS_HANDLER_FUNCTION_NAME'),
			'Role' => 'arn:aws:iam::869029932727:role/api-lambda',
			'Runtime' => 'provided',
		];
	}
}
