#!/bin/sh
DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo $FE

# path_target='~/it490'

# echo "\e[93m"
# echo Pulling..."\e[0m"
 

mkdir -p build
path_package='build/package'
mkdir -p $path_package


cp -r client $path_package
cp -r front_end $path_package
cp -r database $path_package
cp -r datasource $path_package
cp -r libs $path_package
cp -r cfg $path_package

echo "\e[93m"
echo Packing..."\e[0m"

tar -zcvf $path_package.tgz $path_package

echo "\e[93m"
echo Cleaning..."\e[0m"

rm -r build/package

echo "\e[92m"
echo DONE"\e[0m"