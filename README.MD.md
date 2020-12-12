
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

## Setup
This section contains steps about any additional configuration that needs to be done with some packages.
### Hamachi VPN
Install the latest [Hamachi](https://www.vpn.net/linux) package on *all* machines. 
### Apache Web Server
### RabbitMQ
### 
## Deployment
## Features


> Written with [StackEdit](https://stackedit.io/).
<!--stackedit_data:
eyJoaXN0b3J5IjpbNjQ5NDMyODg3LDYyMjUyMDY2MCwxODQ1OD
kxODkyLDQzMTg5OTIwLDg1NTk2MDE1LDkyODY3Njk5NywxMTE1
NzM3MDQxLDkwMTI5NDMyMSwxNDgwNzE0Mzk5LC0xMzYxMjY4Nj
EwLDEyNTcxODcyNzYsMTk0NzkyNjQyMCwtMTYxMDEzMDE0MCwt
Mzc4NjQwNjJdfQ==
-->