<?php

class Aiti_Zapiex_Model_Api_Category extends Mage_Core_Model_Abstract {

    /**
     * Method to retrieve categories
     */
    public function getAliexpressCategories() : array {
        try {
            $httpMethod = Aiti_Zapiex_Model_Api::HTTP_GET;
            $url = Mage::getStoreConfig('aiti_zapiex/api_category_endpoint/categories_endpoint_url');
            $data = array(); // GET method with no data
            $auth = array(); // Categories endpoint doesn't require authentication

            return Mage::getModel('aiti_zapiex/api')->callZapiexApi($httpMethod, $url, $data, $auth);

        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex_api_category.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }
}