#!/usr/bin/env bash

XDEBUG_VER=3.2.2
PHP_VER=8.2.10
PHP_V=8.2

sudo dnf makecache --refresh
sudo dnf -y update

# Check if firewalld is installed:
#sudo systemctl is-enabled firewalld
sudo dnf install firewalld

# Enable and start firewalld:
sudo systemctl enable firewalld
sudo systemctl start firewalld
# Allow port 80 (HTTP):
sudo firewall-cmd --add-port=8000/tcp --permanent
# Reload firewalld to apply the changes:
sudo firewall-cmd --reload

# Check the status of the firewall rules:
sudo firewall-cmd --list-ports

sudo dnf -y install nano unzip libxml2-devel sqlite-devel libcurl-devel libpng-devel  #libsodium-devel
sudo dnf --enablerepo=crb -y install libzip-devel oniguruma-devel

wget http://nl1.php.net/distributions/php-$PHP_VER.tar.gz
tar -xzf php-$PHP_VER.tar.gz
cd php-$PHP_VER

./configure --sysconfdir=/etc/php/$PHP_V/cli --with-config-file-path=/etc/php/$PHP_V/cli --with-config-file-scan-dir=/etc/php/$PHP_V/cli/conf.d \
    --disable-cgi --enable-cli --prefix=/usr --with-openssl --with-curl --with-zip --with-zlib --enable-mbstring --enable-gd --enable-ftp # --with-sodium

make -j$(nproc) && sudo make install

sudo mv /usr/bin/php /usr/bin/php$PHP_V && sudo ln -s /usr/bin/php$PHP_V /usr/bin/php
sudo mkdir -p /etc/php/$PHP_V/cli/conf.d
cp php.ini-production /etc/php/$PHP_V/cli/php.ini

cd .. && rm -rf php-$PHP_VER php-$PHP_VER.tar.gz

# Xdebug
wget https://github.com/xdebug/xdebug/archive/refs/tags/$XDEBUG_VER.tar.gz -O xdebug-$XDEBUG_VER.tar.gz
tar -xzf xdebug-$XDEBUG_VER.tar.gz
cd xdebug-$XDEBUG_VER
sudo ./rebuild.sh
cd .. && rm -rf xdebug-$XDEBUG_VER.tar.gz xdebug-$XDEBUG_VER

cat << EOF > /etc/php/$PHP_V/cli/conf.d/xdebug.ini
zend_extension=xdebug.so
xdebug.mode=debug
xdebug.idekey=PHPSTORM
xdebug.start_with_request=trigger
xdebug.client_host=10.0.2.2
xdebug.client_port=9003
EOF

# Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Symfony CLI
#  wget https://github.com/symfony-cli/symfony-cli/releases/latest/download/symfony-cli_linux_amd64.tar.gz
wget https://get.symfony.com/cli/installer -O - | bash && sudo mv ~/.symfony5/bin/symfony /usr/bin/symfony
