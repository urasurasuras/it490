#!/bin/sh
vb1=$(awk -F "=" '/VB1/ {print $2}' targets.ini)
echo $vb1
vb2=$(awk -F "=" '/VB2/ {print $2}' targets.ini)
echo $vb2
vb3=$(awk -F "=" '/VB3/ {print $2}' targets.ini)
echo $vb3

# echo testecho
# scp -r $vb1:it490 ./
# tar -zcvf it490.tgz it490/
# rm -r it490
#ssh user@remote "sudo scp -r user@local:/path/to/files /opt/bin"

echo Pulling...
echo

mkdir package

# Assume all scripts are in /it490

# Pull libs and configs
mkdir package/libs
scp -r $vb3:~/it490/*.inc ./package/libs

# Pull Client scripts
mkdir package/client
scp -r $vb3:~/it490/FrontEnd* ./package/client

# Pull DB scripts
mkdir package/database
scp -r $vb2:~/it490/DB* ./package/database
# TODO: Export rabbitmq definitions

# Pull DMZ scripts
mkdir package/datasource
scp -r $vb3:~/it490/DMZ* ./package/datasource

# Pull web page
scp -r $vb1:/var/www/html ./package

echo Packing...
echo

mkdir build
tar -zcvf build/package.tgz package

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
