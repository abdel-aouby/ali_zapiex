<?php

class Aiti_Zapiex_Model_Api_SearchProducts extends Mage_Core_Model_Abstract  {

    protected function _construct () {
        parent::_construct();
    }

    /**
     * Method to search product by category ID
     */
    public function searchProductsByCategoryId(string $searchCategoryId, int $page, float $minPrice, float $maxPrice) {
        try {
            $httpMethod = Aiti_Zapiex_Model_Api::HTTP_POST;
            $url = Mage::getStoreConfig('aiti_zapiex/api_search_products_endpoint/search_products_endpoint_url');

            $data = array(
                'searchCategoryId' => $searchCategoryId,
                'shipFrom' => 'CN',
                'shipTo' => Mage::getStoreConfig('aiti_zapiex/api_search_products_endpoint/search_products_ship_to'),
                'sort' => 'NUMBER_OF_ORDERS',
                'page' => $page,
            );

            if ($minPrice && $maxPrice && $minPrice < $maxPrice) {
                $data['priceRange'] = array(
                    'from' => $minPrice,
                    'to' => $maxPrice
                );
            }

            $auth = array(
                'name' => Mage::getStoreConfig('aiti_zapiex/api_config/api_header_name'),
                'key' => Mage::getStoreConfig('aiti_zapiex/api_config/api_key')
            );

            return Mage::getModel('aiti_zapiex/api')->callZapiexApi($httpMethod, $url, $data, $auth);

        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex_api_search_product.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }

    /**
     * Method to search product by store ID
     */
    public function searchProductsByStoreId(string $searchStoreId, int $page) {
        try {
            $httpMethod = Aiti_Zapiex_Model_Api::HTTP_POST;
            $url = Mage::getStoreConfig('aiti_zapiex/api_search_products_by_store_endpoint/search_products_by_store_endpoint_url');

            $data = array(
                'storeId' => $searchStoreId,
                'sort' => 'NUMBER_OF_ORDERS',
                'page' => $page,
            );

            $auth = array(
                'name' => Mage::getStoreConfig('aiti_zapiex/api_config/api_header_name'),
                'key' => Mage::getStoreConfig('aiti_zapiex/api_config/api_key')
            );

            return Mage::getModel('aiti_zapiex/api')->callZapiexApi($httpMethod, $url, $data, $auth);
        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex_api_search_product.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }
}
