#!/usr/bin/env bash

## pip is part of your PATH
export PATH=/usr/local/bin:$PATH
source ~/.profile

## install awscli 2
# https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-welcome.html
curl "https://awscli.amazonaws.com/awscli-exe-linux-x86_64.zip" -o "awscliv2.zip"
unzip awscliv2.zip
sudo ./aws/install

aws --version

aws configure
