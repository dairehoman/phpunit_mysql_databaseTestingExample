$tableProductsCreate = <<<EOT

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `quantityInStock` int(11) NOT NULL,
  `restockQuantity` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

EOT;

$tableProductsPrimaryKey = <<<EOT
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);
EOT;

$tableProductsAutoIncrement = <<<EOT

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;

EOT;
