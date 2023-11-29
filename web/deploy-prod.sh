# Install dependencies
composer install --classmap-authoritative --no-dev --no-scripts

# Warmup the cache
bin/console cache:clear --env=prod

# Disable use of Dotenv component
echo "<?php return [];" > .env.local.php

serverless deploy
