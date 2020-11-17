#!/bin/sh
DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo $FE

# echo testecho
# scp -r $DMZ:it490 ./
# tar -zcvf it490.tgz it490/
# rm -r it490
#ssh user@remote "sudo scp -r user@local:/path/to/files /opt/bin"

echo Pulling...
echo

mkdir build
mkdir build/package

# Assume all scripts are in /it490

# Pull libs and configs
mkdir build/package/libs
scp -r $FE:~/it490/*.inc ./build/package/libs

# Pull Client scripts
mkdir build/package/client
scp -r $FE:~/it490/FrontEnd* ./build/package/client

# Pull DB scripts
mkdir build/package/database
scp -r $DB:~/it490/DB* ./build/package/database

# Export rabbitmq definitions
ssh $DB 'rabbitmqadmin export ~/rabbit.definitions.json'
scp -r $DB:~/it490/DB* ./build/package/database
ssh $DB 'rm ~/rabbit.definitions.json'

# Pull DMZ scripts
mkdir build/package/datasource
scp -r $FE:~/it490/DMZ* ./build/package/datasource

# Pull web page
scp -r $DMZ:/var/www/html ./build/package

echo Packing...
echo

tar -zcvf build/package.tgz build/package

echo Cleaning...
echo

rm -r build/package

echo DONE
echo

# TODO: For unpacking:
# 	install rabbitmq and enable management, import defnitions
#	php
#	php-amqp
#	mysql-server
