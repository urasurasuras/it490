#!/bin/sh
DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo $FE

path_target='~/it490'

echo "\e[93m"
echo Pulling..."\e[0m"
 

mkdir -p build
path_package='build/package'
mkdir -p $path_package

# Assume all scripts are in /it490

# Pull libs and configs
mkdir -p $path_package/libs
scp -r $FE:$path_target/*.inc $path_package/libs

# Pull Client scripts
mkdir -p $path_package/client
scp -r $FE:$path_target/FrontEnd* $path_package/client

# Pull DB scripts
mkdir -p $path_package/database
scp -r $DB:$path_target/DB* $path_package/database

# Export rabbitmq definitions
ssh $DB 'rabbitmqadmin export ~/rabbit.definitions.json'
scp -r $DB:$path_target/DB* $path_package/database
ssh $DB 'rm ~/rabbit.definitions.json'

# Pull DMZ scripts
mkdir -p $path_package/datasource
scp -r $FE:$path_target/DMZ* $path_package/datasource

# Pull web page
scp -r $DMZ:/var/www/html $path_package

echo "\e[93m"
echo Packing..."\e[0m"

tar -zcvf $path_package.tgz $path_package

echo "\e[93m"
echo Cleaning..."\e[0m"

rm -r build/package

echo "\e[92m"
echo DONE"\e[0m"


# TODO: For unpacking:
# 	install rabbitmq and enable management, import defnitions
#	php
#	php-amqp
#	mysql-server
