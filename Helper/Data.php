<?php
 
class Aiti_Zapiex_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getMaximumPage() {
        return Mage::getStoreConfig('aiti_zapiex/api_search_products_endpoint/search_products_maximum_page');
    }

    public function sortProductCategories(array $productCategories): array {
        usort($productCategories, function($a, $b) {
            return $a['level'] - $b['level'];
        });

        return $productCategories;
    }

    public function generateCategoriesPath(array $categories): array {
        $paths = array();

        foreach ($categories as $element) {
            $paths[$element['id']] = (isset($paths[$element['parentId']]) ? $paths[$element['parentId']] . ' > ' : '') . $element['enName'];
        }

        return $paths;
    }
}
