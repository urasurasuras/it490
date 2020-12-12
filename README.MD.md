
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
Populate this configuration file with each machine's respective IP addresses
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
eyJoaXN0b3J5IjpbMzM4NzYzNzY0LDE2OTE0MjYxNzMsLTE3Mz
gwMDcxNzEsLTMyNjkyMzk5MywxMzc1NDgxMDgzLDM1MzkwODM4
NSwtODA4MjY2NjI4LC0yMDA4NTAwNTMwLDE1MjM0ODM4MywyMD
EyOTYyNzc0LDEzMDAyNjE3NzAsNjIyNTIwNjYwLDE4NDU4OTE4
OTIsNDMxODk5MjAsODU1OTYwMTUsOTI4Njc2OTk3LDExMTU3Mz
cwNDEsOTAxMjk0MzIxLDE0ODA3MTQzOTksLTEzNjEyNjg2MTBd
fQ==
-->