<?php
namespace ItbTest;

use Itb\Product;

class ProductDBTest extends \PHPUnit_Extensions_Database_TestCase
{
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

    public function testNumRowsFromSeedData()
    {
        // arrange
        $numRowsAtStart = 4;
        $expectedResult = $numRowsAtStart;

        // act

        // assert
        $this->assertEquals($expectedResult, $this->getConnection()->getRowCount('products'));
    }

    public function testRowCountAfterDeleteOne()
    {

        // arrange
        $numRowsAtStart = 4;
        $this->assertEquals($numRowsAtStart, $this->getConnection()->getRowCount('products'), 'Pre-Condition');
        $expectedResult = 3;

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
        $dataFilePath = __DIR__ . '/databaseXml/expectedProductsWithCandle.xml';
        $expectedTable = $this->createXMLDataSet($dataFilePath)
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

    public function testGetAllAsAssociativeArray()
    {

        // arrange
        $expectedResult = [
            [
                'id' => '14',
                'description' => 'forkHandles',
                'price' => '9.99',
                'quantityInStock' => '5',
                'restockQuantity' => '15'
            ],
            [
                'id' => '1',
                'description' => 'nut',
                'price' => '66',
                'quantityInStock' => '20',
                'restockQuantity' => '25'
            ],
            [
                'id' => '5',
                'description' => 'pliers',
                'price' => '9.99',
                'quantityInStock' => '50',
                'restockQuantity' => '10'
            ],
            [
                'id' => '13',
                'description' => 'hammer',
                'price' => '999',
                'quantityInStock' => '27',
                'restockQuantity' => '7'
            ],
        ];

        // act
        $result = Product::getAllAssociative();

        // assert
        $this->assertEquals($expectedResult, $result);
    }
}

