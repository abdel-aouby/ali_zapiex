<?php

class Aiti_Zapiex_Model_Api_RetrieveProduct extends Mage_Core_Model_Abstract {

    /**
     * Method to retrieve product by ID
     * @param string $productId
     * @return array
     * @throws Mage_Core_Exception
     */
    public function retrieveProductById(string $productId) {
        try {
            $httpMethod = Aiti_Zapiex_Model_Api::HTTP_POST;
            $url = Mage::getStoreConfig('aiti_zapiex/api_retrieve_product_endpoint/retrieve_product_endpoint_url');

            $data = array(
                'productId' => $productId,
                'shipTo' => Mage::getStoreConfig('aiti_zapiex/api_search_products_endpoint/search_products_ship_to'),
                'getSellerDetails' => true,
                'getShipping' => true,
                'getHtmlDescription' => true
            );

            $auth = array(
                'name' => Mage::getStoreConfig('aiti_zapiex/api_config/api_header_name'),
                'key' => Mage::getStoreConfig('aiti_zapiex/api_config/api_key')
            );

            return Mage::getModel('aiti_zapiex/api')->callZapiexApi($httpMethod, $url, $data, $auth);

        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex_api_retrieve_product.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }

}
