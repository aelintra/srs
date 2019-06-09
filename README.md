# srs
simple redirect server

REQUIRES 
lamp
composer zip php-curl php-xml php-mbstring php-zipopenssh openssh-server
SLIM PHP framework

OPTIONAL
sqlite3 (alternative to mysql/mariadb)

mkdir srs

clone the repo

git clone https://github.com/slimphp/Slim-Skeleton.git sysAPI
cd sysAPI
composer install

git clone https://github.com/slimphp/Slim-Skeleton.git sysRedir
cd sysRedir
composer install
