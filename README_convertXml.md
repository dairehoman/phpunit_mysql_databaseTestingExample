convert the XML to the one PHPUnit understands

1 - delete all lines up to (but not including) the <database> line:
<database name="itb_test">

2 - remove the last line of the file
</pma_xml_export>

3 - change the entity name from 'database' to 'dataset'
(the closing tag at the end of the file should also have its entity name changes to 'dataset')

4 - insert the following to be the first line
<?xml version="1.0" ?>

5 - convert XML format from 'table-table-table' to 'table-columns-row-row-row'
unfortunately the XML format understood by PHPUnit is different to that exportred by MySQL

for each table MySQL exports as follows:

        <!-- Table products -->
        <table name="products">
            <column name="id">14</column>
            <column name="description">forkHandles</column>
            <column name="price">9.99</column>
            <column name="quantityInStock">5</column>
            <column name="restockQuantity">15</column>
        </table>
        <table name="products">
            <column name="id">1</column>
            <column name="description">nut</column>
            <column name="price">66</column>
            <column name="quantityInStock">20</column>
            <column name="restockQuantity">25</column>
        </table>

PHPUnit expects data in this format:
(in fact it doesn't need all those 'name=' attributes but they are just ignored so no need to remove them)

    <table name="products">
        <column name="id">id</column>
        <column name="description">description</column>
        <column name="price">price</column>
        <column name="quantityInStock">quantityInStock</column>
        <column name="restockQuantity">restockQuantity</column>

        <row name="products">
            <value name="id">14</value>
            <value name="description">forkHandles</value>
            <value name="price">9.99</value>
            <value name="quantityInStock">5</value>
            <value name="restockQuantity">15</value>
        </row>
        <row name="products">
            <value name="id">1</value>
            <value name="description">nut</value>
            <value name="price">66</value>
            <value name="quantityInStock">20</value>
            <value name="restockQuantity">25</value>
        </row>
    </table>

here are the 4 simple steps to convert EACH TABLE:
(this is quick and easy if you know how to use search/replace IDE editor features)

(step a)
copy the ```<table>``` element and wrap it around all entries for that table, e.g.:

    <?xml version="1.0" ?>
    <dataset name="itb_test">
        <!-- Table products -->
        <table name="products">
            <table name="products">
                <column name="id">14</column>
                <column name="description">forkHandles</column>
                <column name="price">9.99</column>
                <column name="quantityInStock">5</column>
                <column name="restockQuantity">15</column>
            </table>
            <table name="products">
                <column name="id">1</column>
                <column name="description">nut</column>
                <column name="price">66</column>
                <column name="quantityInStock">20</column>
                <column name="restockQuantity">25</column>
            </table>
        </table>
    </dataset>

(step b)
copy all the ```<column>``` elements of the first element to be the first elements of ```<table>```,
and replace the data vaues with the column names, e.g.:

    <?xml version="1.0" ?>
        <dataset name="itb_test">
            <!-- Table products -->
            <table name="products">
                <column name="id">id</column>
                <column name="description">description</column>
                <column name="price">price</column>
                <column name="quantityInStock">quantityInStock</column>
                <column name="restockQuantity">restockQuantity</column>

(step c)
replace the ```<table>``` element name with <row> for each of the row elements, e.g.:

    <?xml version="1.0" ?>
        <dataset name="itb_test">
            <!-- Table products -->
        <table name="products">
            <column name="id">id</column>
            <column name="description">description</column>
            <column name="price">price</column>
            <column name="quantityInStock">quantityInStock</column>
            <column name="restockQuantity">restockQuantity</column>

            <row name="products">
                <column name="id">14</column>
                <column name="description">forkHandles</column>
                <column name="price">9.99</column>
                <column name="quantityInStock">5</column>
                <column name="restockQuantity">15</column>
            </row>
            <row name="products">
                <column name="id">1</column>
                <column name="description">nut</column>
                <column name="price">66</column>
                <column name="quantityInStock">20</column>
                <column name="restockQuantity">25</column>
            </row>
        </table>
    </dataset>

(step d)
for each ```<row>``` element, rename the ```<column>``` child elements to be ```<value>```, e.g.:

    <?xml version="1.0" ?>
        <dataset name="itb_test">
            <!-- Table products -->
        <table name="products">
            <column name="id">id</column>
            <column name="description">description</column>
            <column name="price">price</column>
            <column name="quantityInStock">quantityInStock</column>
            <column name="restockQuantity">restockQuantity</column>

            <row name="products">
                <value name="id">14</value>
                <value name="description">forkHandles</value>
                <value name="price">9.99</value>
                <value name="quantityInStock">5</value>
                <value name="restockQuantity">15</value>
            </row>
            <row name="products">
                <value name="id">1</value>
                <value name="description">nut</value>
                <value name="price">66</value>
                <value name="quantityInStock">20</value>
                <value name="restockQuantity">25</value>
            </row>
        </table>
    </dataset>
    