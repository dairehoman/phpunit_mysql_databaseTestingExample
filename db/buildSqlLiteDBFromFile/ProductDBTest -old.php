<?php
namespace ItbTest;

use Itb\Product;

class ProductDBTest extends \PHPUnit_Extensions_Database_TestCase
{
    private function createProductsTable(\PDO $pdo)
    {
        $tableProductsCreate = "CREATE TABLE `products` (`id` int(11) NOT NULL,`description` text NOT NULL,`price` float NOT NULL,`quantityInStock` int(11) NOT NULL,`restockQuantity` int(11) NOT NULL) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1";
        $tableProductsPrimaryKey = "ALTER TABLE `products` ADD PRIMARY KEY (`id`)";
        $tableProductsAutoIncrement = "ALTER TABLE `products` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15";

        print $tableProductsCreate;
        print PHP_EOL;
        $query = $pdo->prepare($tableProductsCreate);
        $query->execute();
        $query = $pdo->prepare($tableProductsPrimaryKey);
        $query->execute();
        $query = $pdo->prepare($tableProductsAutoIncrement);
        $query->execute();


    }

    public function getConnection()
    {
        $host = DB_HOST;
        $dbName = DB_NAME;
        $dbUser = DB_USER;
        $dbPass = DB_PASS;

// mysql
//        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName;
//        $db = new \PDO($dsn, $dbUser, $dbPass);
//        $connection = $this->createDefaultDBConnection($db, $dbName);

// sql lite
        $pdo = new \PDO('sqlite::memory:');
        $connection = $this->createDefaultDBConnection($pdo, ':memory:');

        // create DB structure
        $this->createProductsTable($pdo);

        return $connection;
    }

    public function getDataSet()
    {
        $seedFilePath = __DIR__ . '/databaseXml/seed.xml';
        return $this->createXMLDataSet($seedFilePath);
    }

    public function testGetAllFromXML()
    {

        // arrange
        $expectedResult = 4; // num rows in test data

        // act
/*
        var_dump(Product::getAll());
        die();
*/
        // assert
        $this->assertEquals($expectedResult, $this->getConnection()->getRowCount('products'));
    }

    public function testDeleteOne()
    {

        // arrange
        $this->assertEquals(4, $this->getConnection()->getRowCount('products'), 'Pre-Condition');
        $expectedResult = 3; // after 1 deleted

        // act
        Product::delete(1);
        $result = $this->getConnection()->getRowCount('products');

        // assert
        $this->assertNotNull($expectedResult, $result);
    }


    public function testGetAllAsObjectArray()
    {

        // arrange
        $product14 = new Product();
        $product14->setId(14);
        $product14->setDescription('forkHandles');
        $product14->setPrice(9.99);
        $product14->setQuantityInStock(5);
        $product14->setRestockQuantity(15);

        $product1 = new Product();
        $product1->setId(1);
        $product1->setDescription('nut');
        $product1->setPrice(66);
        $product1->setQuantityInStock(20);
        $product1->setRestockQuantity(25);

        $product5 = new Product();
        $product5->setId(5);
        $product5->setDescription('pliers');
        $product5->setPrice(9.99);
        $product5->setQuantityInStock(50);
        $product5->setRestockQuantity(10);

        $product13 = new Product();
        $product13->setId(13);
        $product13->setDescription('hammer');
        $product13->setPrice(999);
        $product13->setQuantityInStock(27);
        $product13->setRestockQuantity(7);


        $expectedResult = [];
        $expectedResult[] = $product14;
        $expectedResult[] = $product1;
        $expectedResult[] = $product5;
        $expectedResult[] = $product13;

        
        /*
        $expectedResult = [
            [
                'id' => 14,
                'description' => 'forkHandles',
                'price' => 9.99,
                'quantityInStock' => 5,
                'restockQuantity' => 15
            ],
            [
                'id' => 1,
                'description' => 'nut',
                'price' => 66,
                'quantityInStock' => 20,
                'restockQuantity' => 25
            ],
            [
                'id' => 5,
                'description' => 'pliers',
                'price' => 9.99,
                'quantityInStock' => 50,
                'restockQuantity' => 10
            ],
            [
                'id' => 13,
                'description' => 'hammer',
                'price' => 999,
                'quantityInStock' => 27,
                'restockQuantity' => 7
            ],
        ];
        */

        // act
        $result = Product::getAll();

        // assert
        $this->assertEquals($expectedResult, $result);

    }

    public function testDatabaseContainsNewlyInsertedProduct()
    {
        // arrange
        $product = new Product();
        $product->setDescription('candle');
        $product->setPrice(1.99);
        $product->setQuantityInStock(100);
        $product->setRestockQuantity(105);


        // create variable containing expected dataset (from XML)
        $seedFilePath = __DIR__ . '/databaseXml/expectedProductsWithCandle.xml';
        $expectedTable = $this->createXMLDataSet($seedFilePath)
            ->getTable('products');

        // act
        // add item to table in our test DB
        Product::insert($product);

        // retrieve dataset from our test DB
        $productsInDatabaseAfterInsert = $this->getConnection()->createQueryTable(
            'products', 'SELECT * FROM products'
        );

        // assert
        $this->assertTablesEqual($expectedTable, $productsInDatabaseAfterInsert);
    }
}

