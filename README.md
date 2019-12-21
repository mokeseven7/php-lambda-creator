#

# Getting Started

This composer library assists the in the creation, configuration, and testing of AWS Lambda function's. It utilizes the AWS Lambda custom runtime API in order to achieve executing PHP (which is not nativly supported by lambda), inside of the lambda environment. This repo contains a pre-compiled PHP 7.3 binary in order to assist you getting up and running quickly. If you have a need compile your own binary, for example, to enable different PHP extensions, I have written a tutorial on medium, which can be found [here](https://medium.com/@mike_48770/php-and-the-aws-lambda-custom-runtime-part-1-8ad94c622701)

If you have any feedback, suggestions, or would like to help improve this tool. I welcome PR's, or suggestions in the form of opening issues. I created this tool to try an make Lambda more approachable for fellow PHP developers, and would be more than happy to assist you should you have any trouble getting up and running.

In order to use this tool, you will need the following:

1. Knowledge of AWS, and an AWS Developer account, with the ability to create IAM api users.

2. Intermediate to advanced level understanding of PHP.

3. Curiosity about lambda!

## Installation

Downloading the tool can be achieved by simply cloning the repo, or fetching this repo via composer.

```bash
$ composer require popcorn/lambda
```

## Configuration

Copy the example configuration file, name the new file .env, and set the aws credentials.

```bash
$ cp .env.example .env
```
