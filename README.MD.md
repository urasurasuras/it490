
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
Install the latest [Hamachi](https://www.vpn.net/linux) package on *all* machines. Since Hamachi allows only allows 5 clients per network, each environment will have their own network that will also include the deployment machine on each of those networks. 
### Apache Web Server
### RabbitMQ
### 
## Deployment
## Features


> Written with [StackEdit](https://stackedit.io/).
<!--stackedit_data:
eyJoaXN0b3J5IjpbLTIwMDg1MDA1MzAsMTUyMzQ4MzgzLDIwMT
I5NjI3NzQsMTMwMDI2MTc3MCw2MjI1MjA2NjAsMTg0NTg5MTg5
Miw0MzE4OTkyMCw4NTU5NjAxNSw5Mjg2NzY5OTcsMTExNTczNz
A0MSw5MDEyOTQzMjEsMTQ4MDcxNDM5OSwtMTM2MTI2ODYxMCwx
MjU3MTg3Mjc2LDE5NDc5MjY0MjAsLTE2MTAxMzAxNDAsLTM3OD
Y0MDYyXX0=
-->