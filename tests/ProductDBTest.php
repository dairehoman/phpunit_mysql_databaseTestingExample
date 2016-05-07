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
        $product1 = new Product();
        $product1->setId(1);
        $product1->setDescription('forkHandles');
        $product1->setPrice(9.99);
        $product1->setQuantityInStock(5);
        $product1->setRestockQuantity(15);

        $product2 = new Product();
        $product2->setId(2);
        $product2->setDescription('nut');
        $product2->setPrice(66);
        $product2->setQuantityInStock(20);
        $product2->setRestockQuantity(25);

        $product3 = new Product();
        $product3->setId(3);
        $product3->setDescription('pliers');
        $product3->setPrice(9.99);
        $product3->setQuantityInStock(50);
        $product3->setRestockQuantity(10);

        $product4 = new Product();
        $product4->setId(4);
        $product4->setDescription('hammer');
        $product4->setPrice(999);
        $product4->setQuantityInStock(27);
        $product4->setRestockQuantity(7);

        $expectedResult = [];
        $expectedResult[] = $product1;
        $expectedResult[] = $product2;
        $expectedResult[] = $product3;
        $expectedResult[] = $product4;

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
                'id' => '1',
                'description' => 'forkHandles',
                'price' => '9.99',
                'quantityInStock' => '5',
                'restockQuantity' => '15'
            ],
            [
                'id' => '2',
                'description' => 'nut',
                'price' => '66',
                'quantityInStock' => '20',
                'restockQuantity' => '25'
            ],
            [
                'id' => '3',
                'description' => 'pliers',
                'price' => '9.99',
                'quantityInStock' => '50',
                'restockQuantity' => '10'
            ],
            [
                'id' => '4',
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

