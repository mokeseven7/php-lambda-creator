#

# Getting Started

This composer library assists in the creation, configuration, and testing of an AWS Lambda function. It utilizes the AWS Lambda custom runtime API in order to achieve executing PHP (which is not natively supported by lambda), inside of the lambda environment. This repo contains a pre-compiled PHP 7.3 binary in order to assist you in getting up and running quickly. If you have a need to compile your own binary, for example, to enable different PHP extensions, I have written a tutorial on medium, which can be found [here](https://medium.com/@mike_48770/php-and-the-aws-lambda-custom-runtime-part-1-8ad94c622701)

If you have any feedback, suggestions, or would like to help improve this tool, I welcome PR's, or suggestions in the form of opening issues. I created this tool to try an make Lambda more approachable for my fellow PHP developers, and would be more than happy to assist you should you have any trouble getting up and running.

In order to use this tool, you will need the following:

1. Knowledge of AWS, and an AWS Developer account, with the ability to create IAM api users.

2. Intermediate to advanced level understanding of PHP.

3. aws cli version 1 or 2.

4. Curiosity about lamba!

## Installation

Downloading the tool can be achieved by simply cloning the repo, or fetching this repo via composer.

```bash
$ composer require popcorn/lambda
```

If you go the composer route, take careful note of any of the below steps that contain instructions pertaining to directory structure/naming.

## Configuration

In the root directory of where you have installed this tool, copy the example configuration file to a new file named .env

```bash
$ cp vendor/popcorn/lambda/.env.example .env
```

In order to create the two "layers" we'll need in order to actually execute our PHP code, we will use the aws cli. Download and install the aws CLI (both version 1 and version 2 will work for the purposes of this libary).

During installation of the aws cli, you will need to create an API IAM user for the cli to authenticate with. in general, the aws cli user is highly privledged, so ensure these credentials do not make it into version control. Copy/Paste the key and secret into the .env file as the values for the following keys, AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY.

After the aws cli is installed, go back into the AWS web console, and create an IAM role (not user) with the following privledges:

1. AWSLambdaFullAccess
2. AmazonS3FullAccess

This role will be used by the AWS PHP SDK to create the lambda functions. Once you have created this role, make a note of the ARN, and paste it into the environment file as the value for AWS_LAMBDA_IAM_ARN.

All lamba functions (as of December 2019), must live in the us-east-1 one availability zone, so set that value in the .env file as well.

AWS_REGION=us-east-1

Finally, set the AWS_LAMBDA_VERSION in the .env file to 'latest'.

## Background Information.

If you aren't familiar with how lambda works, I will take a moment to explain some of the terminology. If you feel comfortable using lambda, please feel free to jump to the [next section](#building-the-layers).

AWS lambda is a service AWS exposes to allow developers to ship code as small units. While a lambda function does not have to consist of single function, as developers, it is often most effective to shift our thinking to this type of arcitecture. When lambda first came out back in 2014, it supported a few langauges, and PHP was not one of them. While it was techincally still possible to execute PHP via process bridge with node, it was very complicated, and the developer experience was, well, shit.

In 2017, AWS announced general avaiabllity of something called the custom runtime API, which allowed developers to execute any programming laugage that was capable of being boostraped within the confinded of the greater lambda rules.

At its core, the lambda service is nothing more than on demand docker container, capable of being spun up with nothing more then an web request being sent to it. These containers are short lived, which means they are not running when they are not executing your program. The pricing model of something like this is whats drawn alot of business's curiosity and eventual adoption of this type of arcitecture.

In addition to this custom runtime api iteself, lambda gives us something called "layers". These layers sit below our actual function code, and allow us, for example, to build up a container in which the php binary is accessible.

## Building The Layers

All of the code necessary to build the runtime and vendor layers exists in the "build" directory. This directory contains the following files:

```
| - bin/
|   - php
| - vendor/
| - bootstrap
| - build.bash
| - composer.json
```

1. `bin/php` - Contains the php 7.3 executable.
2. `bootstrap` - This file is the brain of our program. It handles instructing lambda on how our code should be executed, and is responsible for handling requests from the runtime API to our lambda code. If you would like a more in depth explanation of how (and why) this code works, check out my medium article [here](https://medium.com/@mike_48770/php-and-the-aws-lambda-custom-runtime-part-1-8ad94c622701#c179).
3. `build.bash` - This is a helper script I created to assist zipping up our layer files, and sending them lambda via the aws cli. You are free to alter this script to your needs, or simply run them in a terminal. If you execute the file directly, ensure you have made the file executable - `chmod +x bootstrap`
4. `composer.json` contains the required libraries for our layers. In this case, I have opted to use guzzle instead of native curl, so we have a single library. Note - Do not confuse this composer.json for the composer.json in the root. The composer.json in the build directory is for depencies of the runtime layer only. If you need other libraries for your actual function code, we will define those inside of the `function` directory.

## Creating a lambda function

In the root directory of your project, create an index file that we can execute.

```bash
$ touch index.php && chmod +x index.php
```

Paste the following example code into the newly created index.php file.

```php
<?php

require "./vendor/autoload.php";

use Popcorn\PHPLambda\Lambda;

$lambdaName = 'my-function';

$lambda = new Lambda($lambdaName);
$lambda->createOrUpdate($lambdaName);


?>
```
