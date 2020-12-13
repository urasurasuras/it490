
# IT 490 JUKZ - Overwatch 
## Environment Structure
1. Deployment System 
2. Development Environment
	* Front End
	* Back End
	* DMZ 
3. Quality Assurance Environment
	* Front End
	* Back End
	* DMZ 
5. Production Environment
	* Front End
		* Failover
	* Back End
		* Failover
	* DMZ 
		* Failover

## Prerequisites
Prerequisites are packages that need to be installed before setup and deployment. All of these packages need to be installed on their respective machines using ``$ sudo apt install <package-name>``

**All Environment Machines**
``php``
``php-amqplib``
``openssh-server``
Any VPN, although we'll be using [LogMeIn Hamachi](#hamachi-vpn) 

**Front end**
[``apache2``](#apache-web-server)

**Back end**
[``mysql-server``](#mysql-server)
[``rabbitmq-server``](#rabbitmq)

**DMZ**
_No additional packages required_
## Source Code Setup
Clone the source code [repository](https://github.com/urasurasuras/it490)
``targets.ini`` will have destination IP addresses for each machine.
Populate this configuration file with each machine's respective IP addresses in your virtual private network.

``packfromsource.sh`` will compile a tar package with the version number in the ``build`` folder.
``Usage ./packfromsource.sh -v [version number]``

``unpack.sh`` will deploy a given tar package to the machines referenced in ``targets.ini``.
This will create ``deployment/`` directories in each of the machines that contains:
* ``cfg/`` contains ``.ini`` files for various configurations
* ``libs/`` contains all common libraries used by the scripts
* ``client/`` contains all the Front End scripts prefixed with ``FrontEnd_*.php``
* ``database/`` contains all the Database scripts prefixed with  ``DB_*.php``
* ``datasource/`` contains all the DMZ scripts prefixed with ``DMZ_*.php``
* ``front_end/`` is the folder that contains the web page (this folder will be placed into ``/var/www/`` as is, therefore it needs to be configured for Apache with that folder name as an enabled site)
* ``version.ini`` contains metadata about the package


``packfromdeployment.sh`` will compile a tar package with the version number in the build folder, pulling the code from ``deployment/`` folder in each of the machines referenced in ``targets.ini``

``uninstall.sh`` will remove the ``deployment/`` folder on each machine referenced in ``targets.ini``. All other configurations such as RabbitMQ definitions, Apache configurations, and the webpage itself.

## Setup
This section contains steps about any additional configuration that needs to be done with some packages.
### Hamachi VPN
Install the latest [Hamachi](https://www.vpn.net/linux) package on *all* machines. Since Hamachi allows only allows 5 clients per network, each environment will have their own network that will also include the deployment machine on each of those networks. 
### Apache Web Server
The web page will be deployed into ``/var/www/front_end`` when the deployment script runs. A respective ``.conf`` will need to be set up and pointed to the web page folder.
### RabbitMQ
### 
## Deployment
## Features


> Written with [StackEdit](https://stackedit.io/).
<!--stackedit_data:
eyJoaXN0b3J5IjpbLTExMDYwNzA2ODgsMTQ1NjIwNDAyNywyND
U3MjcwMDAsLTEyNTczMTE5ODMsLTY0MDE5Mzc5MSwxOTI2NzU2
MTA3LC00MzA5OTAxMjMsNjY0MDAxODIsMTg5MzQ1MjIwNCwxMj
A3MjUwMDUwLC0zOTgxMTk4MzcsLTczMTAwMTUzMiwzMzg3NjM3
NjQsMTY5MTQyNjE3MywtMTczODAwNzE3MSwtMzI2OTIzOTkzLD
EzNzU0ODEwODMsMzUzOTA4Mzg1LC04MDgyNjY2MjgsLTIwMDg1
MDA1MzBdfQ==
-->