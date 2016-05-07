# count the rows of a table - after database seeding

to get the nuymebr of rows we use:

    $this->getConnection()->getRowCount(<tableName>)


----- the code -------

    public function testNumRowsFromSeedData()
    {
        // arrange
        $numRowsAtStart = 4;
        $expectedResult = $numRowsAtStart;
    
        // act
    
        // assert
        $this->assertEquals($expectedResult, $this->getConnection()->getRowCount('products'));
    }

# count the rows of a table - after deleting a row
note - there is a 'pre-condition' message after asserting there were 4 rows BEFORE the action
this is when it makes sense to have more than one assertion in a test method


---- the code ------

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

# if we are testing methods that CHANGE the database contents
we can prepare an 'expectedResult' XML dataset to compare with the actual changed database

in this example file 'expectedProductsWithCandle.xml' contains the expected final dataset
(with new product recortd for 'candle')

once again we use:

    $this->createXMLDataSet($seedFilePath)
  
to get PHPUnit to create a dataset from an XML file (just like public function getDataSet()

----- the code --------
  
  
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
  
# we don't have to count rows or compare with XML
we can test what actual methods return

note - this could be done with mocking, so we don't need the test DB at all
but since we've setup a DB we'll use it for this example

the pdo-crud-for-free method ::getAll returns an array of objects
so we can create an $expectedResult containing an array of objects to compare with that methods result

---- the code -----

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

# while pdo-crud-for-free returns arrays of objects
many DB code works with associative arrays

so here is an example of a method returning associative arrays:
  
      // in Product.php
      public static function getAllAssociative()
      {
          $dbManager = new DatabaseManager();
          $connection = $dbManager->getDbh();
  
          $sql = 'SELECT * from products';
  
          $statement = $connection->prepare($sql);
          // set FETCH MODE to associative array
          $statement->setFetchMode(\PDO::FETCH_ASSOC);
  
          $statement->execute();
  
          $objects = $statement->fetchAll();
          return $objects;
      }
  
and here is a method to compare an $expectedResult containing an associateive array with the methods actual values returned from the databas:

------ the code ---------
  
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
