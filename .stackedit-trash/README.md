---


---

<h1 id="it-490-jukz---overwatch">IT 490 JUKZ - Overwatch</h1>
<h2 id="environment-structure">Environment Structure</h2>
<ol>
<li>Deployment System</li>
<li>Development Environment
<ul>
<li>Front End</li>
<li>Back End</li>
<li>DMZ</li>
</ul>
</li>
<li>Quality Assurance Environment
<ul>
<li>Front End</li>
<li>Back End</li>
<li>DMZ</li>
</ul>
</li>
<li>Production Environment
<ul>
<li>Front End
<ul>
<li>Failover</li>
</ul>
</li>
<li>Back End
<ul>
<li>Failover</li>
</ul>
</li>
<li>DMZ
<ul>
<li>Failover</li>
</ul>
</li>
</ul>
</li>
</ol>
<h2 id="prerequisites">Prerequisites</h2>
<p>Prerequisites are packages that need to be installed before setup and deployment. All of these packages need to be installed on their respective machines using <code>$ sudo apt install &lt;package-name&gt;</code></p>
<p><strong>All Environment Machines</strong><br>
<code>php</code><br>
<code>php-amqplib</code><br>
<code>openssh-server</code><br>
Any VPN with static IP should be fine, although we’ll be using <a href="#hamachi-vpn">LogMeIn Hamachi</a></p>
<p><strong>Front end</strong><br>
<a href="#apache-web-server"><code>apache2</code></a></p>
<p><strong>Back end</strong><br>
<a href="#mysql-server"><code>mysql-server</code></a><br>
<a href="#rabbitmq"><code>rabbitmq-server</code></a></p>
<p><strong>DMZ</strong><br>
<em>No additional packages required</em></p>
<h2 id="setup">Setup</h2>
<p>This section contains steps about any additional configuration that needs to be done with some packages.</p>
<h3 id="hamachi-vpn">Hamachi VPN</h3>
<p>Install the latest <a href="https://www.vpn.net/linux">Hamachi</a> package on <em>all</em> machines. Since Hamachi allows only allows 5 clients per network, each environment will have their own network that will also include the deployment machine on each of those networks.</p>
<h3 id="apache-web-server">Apache Web Server</h3>
<p>The web page will be deployed into <code>/var/www/front_end</code> when the deployment script runs. A respective <code>.conf</code> will need to be set up and pointed to the web page folder.</p>
<h3 id="rabbitmq">RabbitMQ</h3>
<p>Enable the management plugin, the definitions will be imported during deployment</p>
<h3 id="mysql-server">MySQL Server</h3>
<p>Set up the default super user:<br>
<code>mysql&gt; CREATE USER 'testuser'@'localhost' IDENTIFIED BY '12345';</code><br>
<code>mysql&gt; GRANT ALL PRIVILEGES ON * . * TO 'testuser'@'localhost';</code><br>
<code>mysql&gt; FLUSH PRIVILEGES;</code></p>
<p>After deployment, run the <code>DB_CreateDB_Script.php</code> script in order to create the database and tables.</p>
<h2 id="deployment">Deployment</h2>
<p>Clone the source code <a href="https://github.com/urasurasuras/it490">repository</a><br>
<code>targets.ini</code> will have destination IP addresses for each machine.<br>
Populate this configuration file with each machine’s respective IP addresses in your virtual private network.</p>
<p><code>packfromsource.sh</code> will compile a tar package with the version number in the <code>build</code> folder.<br>
<code>Usage ./packfromsource.sh -v [version number]</code></p>
<p><code>unpack.sh</code> will deploy a given tar package to the machines referenced in <code>targets.ini</code>.<br>
This will create <code>deployment/</code> directories in each of the machines that contains:</p>
<ul>
<li>
<p><code>cfg/</code> contains <code>.ini</code> files for various configurations</p>
</li>
<li>
<p><code>libs/</code> contains all common libraries used by the scripts</p>
</li>
<li>
<p><code>client/</code> contains all the Front End scripts prefixed with <code>FrontEnd_*.php</code></p>
</li>
<li>
<p><code>database/</code> contains all the Database scripts prefixed with  <code>DB_*.php</code> and <code>rabbit.definitions.json</code> that will be imported to <a href="#rabbitmq"><code>RabbitMQ</code></a></p>
</li>
<li>
<p><code>datasource/</code> contains all the DMZ scripts prefixed with <code>DMZ_*.php</code></p>
</li>
<li>
<p><code>front_end/</code> is the folder that contains the web page (this folder will be placed into <code>/var/www/</code> as is, therefore it needs to be configured for Apache with that folder name as an enabled site)</p>
</li>
<li>
<p><code>version.ini</code> contains metadata about the package</p>
</li>
</ul>
<p><code>packfromdeployment.sh</code> will compile a tar package with the version number in the build folder, pulling the code from <code>deployment/</code> folder in each of the machines referenced in <code>targets.ini</code></p>
<p><code>uninstall.sh</code> will remove the <code>deployment/</code> folder on each machine referenced in <code>targets.ini</code>. All other configurations such as RabbitMQ definitions, Apache configurations, and the webpage itself will remain.</p>

<!--stackedit_data:
eyJoaXN0b3J5IjpbLTEzNzcyMDA0NjldfQ==
-->