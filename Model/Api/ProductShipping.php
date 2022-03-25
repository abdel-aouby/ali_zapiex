<?php

class Aiti_Zapiex_Model_Api_ProductShipping {

    /**
     * Method to retrieve product by ID
     * @param string $productId
     * @param string $countryCode
     * @return array
     * @throws Mage_Core_Exception
     */
    public function retrieveProductShipping(string $productId, string $countryCode) : array {
        try {
            $httpMethod = Aiti_Zapiex_Model_Api::HTTP_POST;
            $url = Mage::getStoreConfig('aiti_zapiex/api_retrieve_product_shipping_endpoint/retrieve_product_shipping_endpoint_url');

            $data = array(
                'productId' => $productId,
                'shipTo' => strtoupper($countryCode)
            );

            $auth = array(
                'name' => Mage::getStoreConfig('aiti_zapiex/api_config/api_header_name'),
                'key' => Mage::getStoreConfig('aiti_zapiex/api_config/api_key')
            );

            return Mage::getModel('aiti_zapiex/api')->callZapiexApi($httpMethod, $url, $data, $auth);

        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex_api_product_shipping.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }

    public function getCheapestShipping(array $productShippingApiResponse): array {

        if (!empty($productShippingApiResponse['data']['isAvailableForSelectedCountries'])
            && is_numeric($productShippingApiResponse['data']['carriers'][0]['price']['value'])
            && !empty($productShippingApiResponse['data']['currency'])
        ) {
            $carriers = $productShippingApiResponse['data']['carriers'];
            $shipping = $carriers[0]['price']['value']; // Initialize the shipping price from first carrier
            $shippingMethod = $carriers[0]['company']['name']; // Initialize the shipping method from first carrier
            $shippingMethodId = $carriers[0]['company']['id'];

            // search the cheapest shipping
            foreach ($carriers as $carrier) {
                if (isset($carrier['price']['value']) && $carrier['price']['value'] < $shipping) {
                    $shipping = $carrier['price']['value'];
                    $shippingMethod = $carrier['company']['name'];
                    $shippingMethodId = $carrier['company']['id'];
                }
            }

            $currency = $productShippingApiResponse['data']['currency'];

            return array(
                'shipping' => $shipping,
                'shipping_method' => $shippingMethod,
                'shipping_method_id' => $shippingMethodId,
                'currency' => $currency
            );
        }

        return array();
    }

}
