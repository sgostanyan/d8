#!/bin/bash

echo "Move to root folder"
cd ../

echo "Composer install"
composer install --no-interaction

echo "Drush updb"
vendor/drush/drush/drush updb -y

echo "Drush cr"
vendor/drush/drush/drush cr

echo "Drush cim"
vendor/drush/drush/drush cim -y

echo "Drush cr"
vendor/drush/drush/drush cr -y
