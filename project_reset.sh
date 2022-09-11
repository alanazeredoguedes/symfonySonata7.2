#! /bin/bash

#echo "Reset Project"

git reset --hard
sleep 2

git clean -f -d
sleep 2

bin/console doctrine:database:drop --force
sleep 2

bin/console doctrine:database:create
sleep 2

bin/console doctrine:schema:create
sleep 2

bin/console fos:user:create --super-admin admin admin@admin admin
sleep 2

bin/console cache:clear
sleep 2

bin/console assets:install --symlink
sleep 2