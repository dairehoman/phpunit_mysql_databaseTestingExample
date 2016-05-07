<?php
require_once __DIR__ . '/../app/config_db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Itb\Product;
$productsNeedingReorder = Product::getAllBelowReorderQuantity();

var_dump($productsNeedingReorder);