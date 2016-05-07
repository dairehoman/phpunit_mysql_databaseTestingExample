# phpunit_mysql_databaseTestingExample

An example project to illustrate database testing with PHPUnit. Illustrated in 10 steps.

    Remember - testing compares actual behaviour (data) against expected behaviour (test data)
    so to unit test database methods we need:
        - a test database (we won't mess about with our 'live' database)
        - test data to 'seed' our test database with (reset before each test)
        - expected results (integers / XML dataaset / arrays - values to compare actual results with)

10 Steps to test Database methods using PHPUnit (and its 'db' component)

# Step 1 - use composer to install the PHPUNIT 'db' component

```

    $ composer require phpunit/dbunit
```

# Step 2 - (if not created) create project folders:

```

    /tests
    /tests/databaseXml
```

# Step 3 - create a copy of your database (we do NOT do testing on a live database!)

I've called my database copy 'itb_test'

note you only need the STRUCTURE of your database
- table contents are removed and re-seeded for each test

# Step 4 - add the following DB constants to file: phpunit.xml

```

    <php>
        <const name="DB_HOST" value="localhost"/>
        <const name="DB_NAME" value="itb_test"/>
        <const name="DB_USER" value="travis"/>
        <const name="DB_PASS" value="travis"/>
    </php>
```

Note - I use 'travis' as user so that all I have to do is remove the password and the settings work with TravisCI 

# Step 5 - export your database tables as XML into file: /tests/databaseXml/seed.xml

# Step 6 - convert the XML to the one PHPUnit understands
(see separate document for these steps)

# Step 7 - ```seed.xml``` represents the 'starting point' for each of your database tests

if you have tests where are you comparing the state of the database AFTER some action(s),
then you will also need to create XML files for each of these 'expectedResult' database states

# Step 8 - change your Test classes to extend PHPUnit_Extensions_Database_TestCase, e.g.:

    class ProductDBTest extends \PHPUnit_Extensions_Database_TestCase

# Step 9 - Database test classes must implement 2 special methods:

getConnection() - this gets a PDO DB connection to the test DB (using the constants from phpunit.xml)
getDataSet() - this uses the XML seed data to create a dataset to be inserted into the database before each test

write the following for theses 2 methods:

    public function getConnection()
    {
        $host = DB_HOST;
        $dbName = DB_NAME;
        $dbUser = DB_USER;
        $dbPass = DB_PASS;

        // mysql
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName;
        $db = new \PDO($dsn, $dbUser, $dbPass);
        $connection = $this->createDefaultDBConnection($db, $dbName);

        return $connection;
    }

    public function getDataSet()
    {
        $seedFilePath = __DIR__ . '/databaseXml/seed.xml';
        return $this->createXMLDataSet($seedFilePath);
    }

# Step 10 - have fun!

you can now write your tests - see the examples and PHPUnit documents for ways to test DB methods


