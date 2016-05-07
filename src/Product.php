<?php
namespace Itb;

use Mattsmithdev\PdoCrud\DatabaseManager;
use Mattsmithdev\PdoCrud\DatabaseTable;

class Product extends DatabaseTable
{
    private $id;
    private $description;
    private $price;
    private $quantityInStock;
    private $restockQuantity;

    /**
     * @return mixed
     */
    public function getQuantityInStock()
    {
        return $this->quantityInStock;
    }

    /**
     * @param mixed $quantityInStock
     */
    public function setQuantityInStock($quantityInStock)
    {
        $this->quantityInStock = $quantityInStock;
    }

    /**
     * @return mixed
     */
    public function getRestockQuantity()
    {
        return $this->restockQuantity;
    }

    /**
     * @param mixed $restockQuantity
     */
    public function setRestockQuantity($restockQuantity)
    {
        $this->restockQuantity = $restockQuantity;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    public static function getAllBelowReorderQuantity()
    {
        $dbManager = new DatabaseManager();
        $connection = $dbManager->getDbh();

        $sql = 'SELECT * from products WHERE quantityInStock < restockQuantity';

        $statement = $connection->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_CLASS, __CLASS__);
        $statement->execute();

        $objects = $statement->fetchAll();
        return $objects;
    }

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

}