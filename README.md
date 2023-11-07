# Composer
```bash
composer audit
composer completion
composer show phpunit/phpunit
```

# Symfony
```bash
echo | symfony server:ca:install
symfony serve --p12=/var/www/certs/localhost.p12

symfony self:update --no-interaction
symfony self:version
symfony self:cleanup
```

# Composer packages
### API
```bash
symfony composer require --no-interaction symfony/validator symfony/serializer symfony/property-access nelmio/cors-bundle gesdinet/jwt-refresh-token-bundle
```

### UUID
```bash
composer require symfony/uid
```

```
# config/services.yaml
services:
    Symfony\Component\Uid\Command\GenerateUlidCommand: ~
    Symfony\Component\Uid\Command\GenerateUuidCommand: ~
    Symfony\Component\Uid\Command\InspectUlidCommand: ~
    Symfony\Component\Uid\Command\InspectUuidCommand: ~
```

```bash
symfony console uuid:generate --random-based
symfony console uuid:generate --time-based=now --node=fb3502dc-137e-4849-8886-ac90d07f64a7
symfony console uuid:generate --count=2 --format=base58
symfony console ulid:generate
symfony console ulid:generate --time="2021-02-02 14:00:00"
symfony console ulid:generate --count=2 --format=rfc4122
symfony console uuid:inspect d0a3a023-f515-4fe0-915c-575e63693998
```

### Mailgun
```bash
symfony composer require --no-interaction symfony/mailgun-mailer moukail/mailgun-mail-status-bundle moukail/password-reset-mail-bundle moukail/verification-mail-bundle
```

### flysystem AWS S3
```bash
symfony composer require --no-interaction league/flysystem-aws-s3-v3:2.0.* league/flysystem-bundle #league/flysystem-cached-adapter
```

### Codeception
#### 1.Install
```bash
symfony composer require --dev codeception/codeception codeception/specify codeception/verify ericmartel/codeception-email-mailhog
symfony composer require --dev league/factory-muffin league/factory-muffin-faker
```

#### 2. Setup
```bash
codecept list
codecept completion
codecept config:validate
codecept bootstrap --namespace App\\Tests
```

Next steps:
1. Edit tests/acceptance.suite.yml to set url of your application. Change PhpBrowser to WebDriver to enable browser testing
2. Edit tests/functional.suite.yml to enable a framework module. Remove this file if you don't use a framework
3. Create your first acceptance tests using codecept g:cest acceptance First
4. Write first test in tests/acceptance/FirstCest.php
5. Run tests using: codecept run
6. Edit Api.suite.yml to enable modules for this suite
7. Create first test with generate:cest testName ( or test|cept) command
8. Run tests of this suite with codecept run Api command

#### 3. Create suite
```bash
codecept generate:suite api
```

#### 4. Create Test
```bash
codecept generate:cest unit User
codecept generate:cest api User
codecept generate:cest acceptance Login
```

###
```bash
symfony composer require --no-interaction symfony/amqp-pack symfony/mercure-bundle
symfony composer require --no-interaction -W sensio/framework-extra-bundle
symfony composer require --no-interaction dompdf/dompdf nyholm/psr7
#symfony/messenger
#twig/extra-bundle
# sentry/sentry-symfony
#dev
symfony composer require --dev flow/jsonpath phpbench/phpbench
symfony composer require --dev vimeo/psalm
```

### Configuration
```bash
symfony composer config extra.symfony.allow-contrib true
symfony composer config allow-plugins.symfony/runtime true
```

# Doctrine
```bash
symfony console doctrine:database:drop --if-exists --force
symfony console doctrine:database:create --if-not-exists
symfony console doctrine:migrations:up-to-date
symfony console doctrine:migrations:migrate --allow-no-migration --no-interaction
symfony console doctrine:schema:update --force
symfony console doctrine:schema:validate
symfony console doctrine:fixtures:load --no-interaction
```

# Nodejs
```bash
npm audit fix
npm install --global browser-sync
npm run dev
symfony run -d npm run watch
```

# Vagrant
```
sudo apt-get install virtualbox
sudo apt install vagrant
vagrant up --provision
vagrant up
vagrant reload
vagrant ssh
```

#### Virtualbox Guest Additions

```
vagrant plugin install vagrant-vbguest
vagrant vbguest --do install --no-cleanup
vagrant vbguest --status
```

```
Vagrant was unable to mount VirtualBox shared folders. This is usually
because the filesystem "vboxsf" is not available. This filesystem is
made available via the VirtualBox Guest Additions and kernel module.
Please verify that these guest additions are properly installed in the
guest. This is not a bug in Vagrant and is usually caused by a faulty
Vagrant box. For context, the command attempted was:

mount -t vboxsf -o uid=1000,gid=1000,_netdev var_www_ /var/www

The error output from the command was:

/sbin/mount.vboxsf: mounting failed with the error: No such device
```

# Xdebug
## Install with Dockerfile
Xdebug =<3.1 support PHP 7.4
```bash
RUN pecl install xdebug-3.1.6 && docker-php-ext-enable xdebug
```

## Using Xdebug in CLI
To check if xdebug is working add a breakpoint in `./bin/console`.

### in Docker container with PHPStorm
#### Option 1
Click on Start listening For PHP Debug Connections button
```bash
export PHP_IDE_CONFIG="serverName=symfony-web"
export XDEBUG_CONFIG="remote_host=host.docker.internal idekey=PHPSTORM"
symfony console
````
#### Option 2
Use idekey
```bash
export PHP_IDE_CONFIG="serverName=symfony-web"
export XDEBUG_CONFIG="remote_host=172.17.0.1 idekey=13233"
symfony console
```

### in Vagrant with PHPStorm
Click on Start listening For PHP Debug Connections button
```bash
export PHP_IDE_CONFIG="serverName=symfony-web"
symfony console
```

# TLS
```bash
mkdir -p config/secrets
openssl genpkey -out config/secrets/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/secrets/private.pem -out config/secrets/public.pem -pubout
```

```bash
mkcert -install
mkcert -key-file /var/www/certs/symfony_key.pem -cert-file /var/www/certs/symfony_cert.pem "app.localhost" "*.app.localhost" "domain.local" "*.domain.local"
openssl pkcs12 -export -out certificate.p12 -inkey symfony_key.pem -in symfony_cert.pem
```

# ngrok
### install ngrok
https://ngrok.com/docs/getting-started/
https://dashboard.ngrok.com/get-started/setup
```bash
sudo snap install ngrok
ngrok config add-authtoken <YOUR_TOKEN>
ngrok http --domain=noted-shad-flexible.ngrok-free.app 8000
ngrok tunnel --label edge=edghts_2W8TyJQmPfk5xw5pp6mxcPcENN5 http://localhost:8000
```

https://theboroer.github.io/localtunnel-www/