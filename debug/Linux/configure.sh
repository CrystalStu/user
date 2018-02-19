#!bin bash
##########################################
# Symfony Debug Server Configure Script  #
# Script by TURX (turuixuan@foxmail.com) #
# Powered by (c) Crystal Studio          #
##########################################
composer install
php bin/console doctrine:schema:update --force