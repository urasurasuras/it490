#!/bin/sh

DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo DMZ: $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo DB: $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo FE: $FE

echo "\e[93m"
echo Deleting..."\e[0m"

ssh $DMZ rm -r ~/deployment
ssh $DB rm -r ~/deployment
ssh $FE rm -r ~/deployment

echo "\e[92m"
echo DONE"\e[0m"
