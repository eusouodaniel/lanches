Lanches
=========

A Symfony project created on November 20, 2017, 2:18 pm.


Rodar projeto localmente(na sua máquina):

php -d memory_limit=2800m composer.phar install

php app/console assets:install

php app/console doctrine:create:database

//Importa o arquivo .sql que tá dentro de database

php app/console doctrine:schema:update --force

php app/console fos:js-routing:dump

php app/console assetic:dump

php app/console server:run