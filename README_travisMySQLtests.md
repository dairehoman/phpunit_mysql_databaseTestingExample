[![Build Status](https://travis-ci.org/dr-matt-smith/travisCItest_silexDatabaseExample.svg?branch=master)](https://travis-ci.org/dr-matt-smith/travisCItest_silexDatabaseExample)

# travisCItest_silexDatabaseExample

This is an example project to illustrate PHPUnit MySQL database testing with TravisCI YAML settings for it all to work properly

based on notes from:
[https://docs.travis-ci.com/user/database-setup/#MySQL](https://docs.travis-ci.com/user/database-setup/#MySQL)

Note - replace ```databaseName``` with the name of your database

## Step 1 - write your Travis YAML file: ```.travis.yml```


    language: php
    php:
      - '7'
    
    services:
      - mysql
    
    before_install:
      - mysql -u root -e "create database IF NOT EXISTS dataBaseName"
      - mysql -u root itb_test < dataBaseName.sql
    
    install:
      - composer update
    
    script:
      - phpunit


So for my example the **dataBaseName** is **itb_test**:

    before_install:
      - mysql -u root -e "create database IF NOT EXISTS itb_test"
      - mysql -u root itb_test < itb_test.sql


Each part explained:

    services:
       - mysql

services - this starts running the mysql server
(this will create a DB runing at 127.0.0.01 - which I assume is 'localhost' as usual)
2 users - 'root' (no password) with full priviedges and 'travis' (no password) less privileges


    before_install:
      - mysql -u root -e "create database IF NOT EXISTS dataBaseName"

this creates new empty DB

      - mysql -u root itb_test < dataBaseName.sql


this sets up DB table structures (data should come from your seeds ...)

## Step 2 - edit the PHPUnit XML configuration file's DB credentials to work with Travis:  ```phpunit.xml```

Set the following:

    DB HOST = 'localhost' 
    DB NAME = dataBaseName
    DB USER = 'travis' 
    DB PASS = '' // NOTE: empty string (no password) 

So for my example the **dataBaseName** is **itb_test** here is my ```phpunit.xml``` file:

    <?xml version="1.0" encoding="utf-8" ?>
    <phpunit bootstrap="./vendor/autoload.php">
    
        <testsuites>
            <testsuite name="The project's test suite">
                <directory>./tests</directory>
            </testsuite>
        </testsuites>
    
        <filter>
            <whitelist>
                <directory>src</directory>
            </whitelist>
        </filter>

        <php>
            <const name="DB_HOST" value="localhost"/>
            <const name="DB_NAME" value="itb_test"/>
            <const name="DB_USER" value="travis"/>
            <const name="DB_PASS" value=""/>
        </php>
        
    </phpunit>


## Step 3 - export the structure of your database as an SQL file (to the root of your project)

(these are steps for PHPMyAdmin)

- select your database (make sure it's the database that is selected and not one of the tables)

- click on Export on the menu bar, select the custom option and ensure format is SQL

- in the Tables section uncheck everything in the data column (we just want the structure)

    - make sure 'Save output to a file' is selected

    - in the Object Creation Options I turn off everything except the AUTO_INCREMENT and 'Enclose table and column names in backquotes' options 
    
    - (don't know if this really matters, the only one that could be a problem is Events since you may not have permission to create them on the server)

    - click Go

You should now have a .sql file that creates your tables, indexes and any constraints you set up.  

Copy this file into the root of your project (and add to git repo)

