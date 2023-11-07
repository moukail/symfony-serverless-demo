# Setup bref
https://bref.sh/docs/setup
### Serverless
```bash
npm install -g serverless
serverless --version
```

### serverless-domain-manager plugin
```bash
serverless plugin install -n serverless-domain-manager
```

### left plugin
```bash
serverless plugin install -n serverless-lift
```

### Deploying for production
```bash
# Create a .env.local.php with dev values
composer dump-env prod
# Install dependencies
composer install --prefer-dist --optimize-autoloader --no-dev

# Warmup the cache
bin/console cache:clear --env=prod
bin/console cache:warmup

# Create an empty .env.local.php to force using environement variables
echo "<?php return ['APP_ENV'=>'prod'];" > .env.local.php

bin/console assets:install --env prod
yarn encore production

serverless deploy --stage=prod

serverless info
serverless logs -f backend --tail
```

### Symfony Console
```bash
serverless bref:cli --args="doctrine:migrations:migrate"
```

```bash
docker compose run web symfony console
```

### To delete the whole application you can run:
```bash
serverless remove --stage=prod
```

https://www.serverless.com/
https://bref.sh/docs/symfony/getting-started
https://github.com/getlift/lift/blob/master/docs/server-side-website.md