<?php

namespace Popcorn\PHPLambda;

use Aws\Credentials\Credentials;
use Aws\Lambda\LambdaClient;

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

	public function getClient()
	{
		return $this->lambda;
	}
}
