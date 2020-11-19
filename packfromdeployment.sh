#!/bin/sh

helpFunction()
{
   echo ""
   echo "Usage: $0 -v version_number"
   echo "\t-v Version number x.y.z"
   # echo -e "\t-b Description of what is parameterB"
   # echo -e "\t-c Description of what is parameterC"
   exit 1 # Exit script after printing help
}

while getopts "v:" opt
do
   case "$opt" in
      v ) version_number="$OPTARG" ;;
      ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
   esac
done

# Print helpFunction in case parameters are empty
if [ -z "$version_number" ] 
then
   echo "Some or all of the parameters are empty";
   helpFunction
fi

# Begin script in case all parameters are correct
echo "Packing v$version_number from source"


DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo DMZ: $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo DB: $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo FE: $FE

path_target='~/deployment'

echo "\e[93m"
echo Pulling..."\e[0m"
 

mkdir -p build
path_package='build/package'
mkdir -p $path_package


# Pull libs and configs
scp -r $DB:$path_target/libs $path_package

# Pull Client scripts
mkdir -p $path_package/client
scp -r $FE:$path_target/client/FrontEnd_* $path_package/client

# Pull DB scripts
mkdir -p $path_package/database
scp -r $DB:$path_target/database/DB_* $path_package/database

# Export rabbitmq definitions
ssh $DB 'rabbitmqadmin export ~/rabbit.definitions.json'
scp -r $DB:~/rabbit.definitions.json $path_package/database
ssh $DB 'rm ~/rabbit.definitions.json'

# Pull DMZ scripts
mkdir -p $path_package/datasource
scp -r $DMZ:$path_target/datasource/DMZ_* $path_package/datasource

# Pull web page
scp -r $FE:/var/www/html $path_package

echo "\e[93m"
echo Packing..."\e[0m"

tar -zcvf $path_package'_v'$version_number.tgz $path_package

echo "\e[93m"
echo Cleaning..."\e[0m"

rm -r build/package

echo "\e[92m"
echo DONE"\e[0m"
