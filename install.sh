#!/usr/bin/env bash

clear
echo "Choose action:"
echo "1 - install project"
echo "2 - update project"
echo "3 - quit"

read Keypress

case "$Keypress" in
1) echo "install start..."
    composer install
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
2) echo "update start..."
    composer update
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
;;
3)
    exit 0
;;
esac

exit 0