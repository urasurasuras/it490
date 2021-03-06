#!/bin/sh

helpFunction()
{
   echo ""
   echo "Usage: $0 -i Package name"
   echo "\t-i Package name"
   # echo -e "\t-b Description of what is parameterB"
   # echo -e "\t-c Description of what is parameterC"
   exit 1 # Exit script after printing help
}

while getopts "i:" opt
do
   case "$opt" in
      i ) package_name="$OPTARG" ;;
      ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
   esac
done

# Print helpFunction in case parameters are empty
if [ -z "$package_name" ] 
then
   echo "Some or all of the parameters are empty";
   helpFunction
fi

# Begin script in case all parameters are correct
echo "Unpacking $package_name"


DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo DMZ: $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo DB: $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo FE: $FE

path_target='~/deployment'
echo "\e[93m"
echo Unpacking..."\e[0m"

# Unpack locally
# path_package='build/open'
# mkdir -p $path_package
tar xzf $package_name 
path_unpackage='build/package'
#echo $path_unpackage

echo "\e[93m"
echo Deploying..."\e[0m"

# Deploy libs to all machines
# TODO: Change lib targets in all scripts
ssh $DB "mkdir -p $path_target"
scp -r $path_unpackage/libs $DB:$path_target
scp -r $path_unpackage/cfg $DB:$path_target
ssh $FE "mkdir -p $path_target"
scp -r $path_unpackage/libs $FE:$path_target
scp -r $path_unpackage/cfg $FE:$path_target
ssh $DMZ "mkdir -p $path_target"
scp -r $path_unpackage/libs $DMZ:$path_target
scp -r $path_unpackage/cfg $DMZ:$path_target

# TODO: For unpacking:
# 	install rabbitmq and enable management, import defnitions
#	ssh $DB 'sudo apt install rabbitmq-server'

# Deploy DB scripts
scp -r $path_unpackage/database $DB:$path_target
# Import rabbitmq-server definitions
ssh $DB "rabbitmqadmin import $path_target/database/rabbit.definitions.json"
# Create database
ssh $DB "php $path_target/database/DB_CreateDB_Script.php"

# Deploy DMZ scripts
scp -r $path_unpackage/datasource $DMZ:$path_target

# Deploy FE web page
scp -r $path_unpackage/front_end $FE:$path_target
ssh $FE "chmod 777 $path_target"
ssh $FE -t "sudo cp -r $path_target/front_end /var/www"
# Deploy FE scripts
scp -r $path_unpackage/client $FE:$path_target
ssh $FE -t "sudo ln -fs $path_target/client/ /var/www/front_end/"


#	php
#	php-amqp
#	mysql-server

echo "\e[93m"
echo Cleaning..."\e[0m"

rm -r build/package

