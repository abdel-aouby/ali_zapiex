<?php

use Tester\Assert;

class GeneratePathsTest extends Aiti_Testing_Model_TestCase {


    protected function _getZapiexCategoriesSorted(): array {
        return [
            [
                "id" => "100003109",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Apparel for Women",
                "enName" => "Women's Clothing",
                "parentId" => "0"
            ],
            [
                "id" => "66",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Beauty & Health",
                "enName" => "Beauty & Health",
                "parentId" => "0"
            ],
            [
                "id" => "509",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Cellphones & Telecommunications",
                "enName" => "Cellphones & Telecommunications",
                "parentId" => "0"
            ],
            [
                "id" => "7",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Computer & Office",
                "enName" => "Computer & Office",
                "parentId" => "0"
            ],
            [
                "id" => "21",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Education & Office Supplies",
                "enName" => "Education & Office Supplies",
                "parentId" => "0"
            ],
            [
                "id" => "502",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Electronic Components & Supplies",
                "enName" => "Electronic Components & Supplies",
                "parentId" => "0"
            ],
            [
                "id" => "2",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Food",
                "enName" => "Food",
                "parentId" => "0"
            ],
            [
                "id" => "1503",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Auto & Moto",
                "enName" => "Auto & Moto",
                "parentId" => "0"
            ],
            [
                "id" => "200002489",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Hair Extensions & Wigs",
                "enName" => "Hair Extensions & Wigs",
                "parentId" => "0"
            ],
            [
                "id" => "6",
                "level" => 1,
                "isLeaf" => false,
                "name" => "Home Appliances",
                "enName" => "Home Appliances",
                "parentId" => "0"
            ],
            [
                "id" => "205776616",
                "level" => 2,
                "isLeaf" => false,
                "name" => "Apparel Accessories",
                "enName" => "Apparel Accessories",
                "parentId" => "509"
            ],
            [
                "id" => "100003070",
                "level" => 2,
                "isLeaf" => false,
                "name" => "Skirts",
                "enName" => "Skirts",
                "parentId" => "100003109"
            ],
            [
                "id" => "34",
                "level" => 2,
                "isLeaf" => false,
                "name" => "Automobiles & Motorcycles",
                "enName" => "Automobiles & Motorcycles",
                "parentId" => "1503"
            ],
            [
                "id" => "15",
                "level" => 2,
                "isLeaf" => false,
                "name" => "Home & Garden",
                "enName" => "Home & Garden",
                "parentId" => "6"
            ],
            [
                "id" => "44",
                "level" => 3,
                "isLeaf" => false,
                "name" => "Consumer Electronics",
                "enName" => "Consumer Electronics",
                "parentId" => "34"
            ],
        ];
    }

    public function testSortZpaiexCategories() {
        $categoriesData = $this->_getZapiexCategoriesSorted(); // get sorted data

        shuffle($categoriesData); // shuffle the sorted data

        $productCategories = Mage::helper('aiti_zapiex')->sortProductCategories($categoriesData); // resort it

        Assert::same(array_column($this->_getZapiexCategoriesSorted(), 'level'), array_column($productCategories, 'level'));
    }

    public function testGenerateCategoriesPath() {
        $productCategories = $this->_getZapiexCategoriesSorted();

        $paths = Mage::helper('aiti_zapiex')->generateCategoriesPath($productCategories);
        Mage::log($paths, null, "marketplace.log", true);
        Assert::same($paths, [
            "100003109" => "Women's Clothing",
            "66" => "Beauty & Health",
            "509" => "Cellphones & Telecommunications",
            "7" => "Computer & Office",
            "21" => "Education & Office Supplies",
            "502" => "Electronic Components & Supplies",
            "2" => "Food",
            "1503" => "Auto & Moto",
            "200002489" => "Hair Extensions & Wigs",
            "6" => "Home Appliances",
            "205776616" => "Cellphones & Telecommunications > Apparel Accessories",
            "100003070" => "Women's Clothing > Skirts",
            "34" => "Auto & Moto > Automobiles & Motorcycles",
            "15" => "Home Appliances > Home & Garden",
            "44" => "Auto & Moto > Automobiles & Motorcycles > Consumer Electronics",

        ]);

    }

}
