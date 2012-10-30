# Quick Introduction #

disclaimer: Eve Framework is in early development stage. It is not yet recommended to use in prodcution!

## 'sites' and 'remotes' ##

Basicialy 'site' is a web-service you will be building. In production environment it can operate under multiple domains (but usually one).
'remote' is a web server that will be serving this service. 

Eve Framweork is designed to be scalable so one site can work on multiple remotes.
On the other hand to serve lots of tiny sites can be served from one remote and thay will share the code and data-base and can be updated/managed all together.

One special remote, called 'dev' is your local development environment. It will serve all available sites and use one database.

## setting up development environment ##

### instalation ###

1.	install apache, php5 and mysql-server

2.	download code from github

	~~~
	git clone git@github.com:fsw/eve.git
	cd eve
	~~~

3.	prepare local database
 
 	~~~
	mysql -u root -p
	CREATE DATABASE eve;
	GRANT ALL ON eve.* TO 'eve'@'localhost' IDENTIFIED BY 'eve';
	QUIT;
	~~~

4.	prepare webroots for local sites
 
 	~~~
	./remote.php push dev
	~~~
	
5.	point apache to corresponding webroots

	

### development package contents ###

1.	cadolibs - 
	set of independent PHP libraries. 
	classes for database access, form generation, simple ORM, templates, mail sending, different provider api access (twitter, facebook) etc. 
	
2.	framework - 
	Eve framework is in face an extension to cadolibs providing simple MVC Framework
	
3.	modules - 
	extensions to Eve framework providing common functionality
	1.	api
	2.	cms
	3.	shop
	4.	lang
	5.	agent

blah blah

### using cadolibs ###


### building first framework website ###

