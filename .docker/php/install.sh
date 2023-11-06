rm -rf ./*
rm -rf ./.*

echo "-------------------------------------------------------------------"
echo "-                create symfony project                           -"
echo "-------------------------------------------------------------------"
symfony self:version

symfony new my_project --no-git --version=5.4 --php=8.2 --docker=false

echo "-------------------------------------------------------------------"
echo "-                   require packages                              -"
echo "-------------------------------------------------------------------"
cd ./my_project
cp .env .env.local

symfony composer require php:^8.2.0
symfony composer require twig symfony/webpack-encore-bundle
symfony composer require --no-interaction doctrine/orm doctrine/doctrine-migrations-bundle ramsey/uuid-doctrine

echo "-------------------------------------------------------------------"
echo "-               require dev packages                              -"
echo "-------------------------------------------------------------------"
symfony composer require --dev debug symfony/profiler-pack symfony/maker-bundle
symfony composer require --dev --no-interaction doctrine/doctrine-fixtures-bundle

symfony composer require --dev --no-interaction codeception/module-symfony codeception/module-doctrine2 \
codeception/module-rest codeception/module-datafactory codeception/module-phpbrowser codeception/module-asserts \
codeception/specify codeception/verify league/factory-muffin league/factory-muffin-faker

echo "-------------------------------------------------------------------"
echo "-                   Init Codeception                              -"
echo "-------------------------------------------------------------------"
codecept bootstrap --namespace App\\Tests
codecept generate:suite api

echo "-------------------------------------------------------------------"
echo "-                          Init Yarn                              -"
echo "-------------------------------------------------------------------"
corepack enable
yarn init -2

echo "-------------------------------------------------------------------"
echo "-                          Ready                                  -"
echo "-------------------------------------------------------------------"
rm -rf .git
cd ..

chmod -R a+rw my_project

rsync -a my_project/ ./
rm -rf my_project
