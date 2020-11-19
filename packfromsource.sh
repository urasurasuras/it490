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


echo "\e[93m"
echo Copying..."\e[0m"

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

tar -zcvf $path_package'_v'$version_number.tgz $path_package

echo "\e[93m"
echo Cleaning..."\e[0m"

rm -r build/package

echo "\e[92m"
echo DONE"\e[0m"