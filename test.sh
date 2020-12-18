# filename = testfile.ini
# echo $filename
# echo "[testServer]" >> $filename
# echo "BROKER_HOST = "xx.xx.xx	>> $filename
# echo "BROKER_PORT = 5672" >> $filename
# echo "USER = test" >> $filename
# echo "PASSWORD = test" >> $filename
# echo "VHOST = testHost" >> $filename
# echo "EXCHANGE = testExchange" >> $filename
# "QUEUE = testQueue" >> $filename
# "AUTO_DELETE = true" >> $filename

DMZ=$(awk -F "=" '/DMZ/ {print $2}' targets.ini)
echo DMZ: $DMZ
DB=$(awk -F "=" '/DB/ {print $2}' targets.ini)
echo DB: $DB
FE=$(awk -F "=" '/FE/ {print $2}' targets.ini)
echo FE: $FE

# If /root/.my.cnf exists then it won't ask for root password
if [ -f /root/.my.cnf ]; then

    mysql -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
    mysql -e "CREATE USER ${MAINDB}@localhost IDENTIFIED BY '${PASSWDDB}';"
    mysql -e "GRANT ALL PRIVILEGES ON ${MAINDB}.* TO '${MAINDB}'@'localhost';"
    mysql -e "FLUSH PRIVILEGES;"

# If /root/.my.cnf doesn't exist then it'll ask for root password   
else
    echo "Please enter root user MySQL password!"
    echo "Note: password will be hidden when typing"
    read -sp rootpasswd
    # mysql -uroot -p${rootpasswd} -e "CREATE DATABASE ${MAINDB} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
    ssh $DB -t 'mysql -uroot -p${rootpasswd} -e "CREATE USER uras@localhost IDENTIFIED BY '12345';"'
    # ssh $DB -t mysql -uroot -p${rootpasswd} -e "GRANT ALL PRIVILEGES ON it490.* TO uras'@'localhost';"
    # ssh $DB -t mysql -uroot -p${rootpasswd} -e "FLUSH PRIVILEGES;"
fi