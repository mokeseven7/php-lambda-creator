#!/usr/bin/env bash

if ! [ -x "$(command -v aws)" ]; then
  echo 'aws cli is not installed. Please Install before continuing.' >&2
  exit 1
fi

if ! [ -x "$(command -v zip)" ]; then
  echo 'The zip utility is not installed, please install before continuing' >&2
  exit 1
fi


# Zip the php binary with the boostrap file
cd build && zip -r runtime.zip bin bootstrap && zip -r vendor.zip vendor/

# Publish the runtime layer
aws lambda publish-layer-version \
    --layer-name php-73-runtime \
    --zip-file fileb://runtime.zip \
    --region us-east-1 > runtime.json

# Publish the vendor layer
aws lambda publish-layer-version \
    --layer-name php-73-vendor \
    --zip-file fileb://vendor.zip \
    --region us-east-1 > vendor.json