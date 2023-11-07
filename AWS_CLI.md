https://docs.aws.amazon.com/lambda/
https://aws.amazon.com/dynamodb/

## Free Tier
- AWS CloudFormation: 1,000 handler operations per month per account
- AWS Lambda: 1,000,000 free requests per month
- Amazon DynamoDB: 25 GB of Storage
## Free Tier 12 MONTHS FREE
- Amazon S3: 5 GB of Standard Storage
- Amazon EC2: 750 hours per month of Linux, RHEL, or SLES t2.micro or t3.micro instance dependent on region
- Amazon RDS
- Amazon ElastiCache: 750 Hours of cache.t2micro or cache.t3.micro Node usage
- Amazon MQ: 750 hours of a single-instance mq.t2.micro or mq.t3 broker per month
- Amazon API Gateway: 1 Million API Calls Received per month

## IAM
https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_access-keys.html#Using_CreateAccessKey_CLIAPI
### To create an access key:
```bash
aws iam create-access-key
```

### To list a user's access keys:
```bash 
aws iam list-access-keys
```

### To list roles:
```bash 
aws iam list-roles
```

### To delete role:
```bash 
aws iam delete-role --role-name symfony-dev-ca-central-1-lambdaRole
```

### cloudfront: list distributions 
```bash
aws cloudfront list-distributions
```

### cloudfront: delete distribution
```bash
aws cloudfront delete-distribution --id EBNNPXK60O88K
```

### CloudFormation: list stacks 
```bash
aws cloudformation list-stacks
```

### CloudFormation: delete stacks 
```bash
aws cloudformation delete-stack --stack-name symfony-dev
```

### S3 list buckets
```bash
aws s3api list-buckets
```

### S3 delete bucket
```bash
aws s3 rm s3://symfony-dev-serverlessdeploymentbucket-1qm9hkkkupeul --recursive
aws s3 rb s3://symfony-dev-serverlessdeploymentbucket-1qm9hkkkupeul --force
#aws s3api delete-bucket --bucket symfony-dev-serverlessdeploymentbucket-f7j4bkncckb6
```

### Labmda list of functions
```bash
aws lambda list-functions
```

### Labmda delete a function
```bash
aws lambda delete-function --function-name symfony-dev-console
aws lambda delete-function --function-name symfony-dev-web
```

## AWS Lambda
https://docs.aws.amazon.com/lambda/latest/dg/welcome.html
https://serverlessland.com/learn/Serverless-LAMP-stack

## Amazon DynamoDB
### Step 1: Create a table
```bash
aws dynamodb create-table \
    --table-name Music \
    --attribute-definitions \
        AttributeName=Artist,AttributeType=S \
        AttributeName=SongTitle,AttributeType=S \
    --key-schema \
        AttributeName=Artist,KeyType=HASH \
        AttributeName=SongTitle,KeyType=RANGE \
    --provisioned-throughput \
        ReadCapacityUnits=5,WriteCapacityUnits=5 \
    --table-class STANDARD
```


### Creating secrets
```bash
aws ssm put-parameter --region ca-central-1 --name '/serverless-demo/prod/app-secret' --type String --value '7ca3adc3815e12d67b4637595b7f9dff'
```

### Retrieving secrets
```bash
aws ssm get-parameter --region ca-central-1 --name '/serverless-demo/prod/app-secret'
aws ssm get-parameter --region ca-central-1 --name '/serverless-demo/prod/database-url'
```