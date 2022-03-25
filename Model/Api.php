<?php

class Aiti_Zapiex_Model_Api extends Mage_Core_Model_Abstract {

    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const JSON_DECODE_DEFAULT_DEPTH = 512;

    /**
     * Method to call zapiex api endpoints
     * @param string $httpMethod
     * @param string $url
     * @param array $data
     * @param array $auth
     * @return array
     * @throws Mage_Core_Exception
     */
    public function callZapiexApi(string $httpMethod, string $url, array $data, array $auth): array {
        try {
            $curl = curl_init();
            $headers[] = 'Content-Type: application/json';

            if (isset($auth['name']) && isset($auth['key'])) {
                $headers[] = $auth['name'] . ': ' . $auth['key'];
            }

            if ($httpMethod == self::HTTP_POST) {
                $data = json_encode($data);
                $headers[] = 'Content-Length: ' . strlen($data);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            } elseif ($httpMethod == self::HTTP_GET && !empty($data)) {
                Mage::throwException(Mage::helper('aiti_zapiex')->__('Data parameters are not allowed in GET method.'));
            }

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $httpMethod);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

            $jsonResult = curl_exec($curl);
            $error = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $errorCode = curl_errno($curl);

            if ($jsonResult === false || ($httpMethod == self::HTTP_GET && ($errorCode || $httpCode != 200))) {
                Mage::throwException('Failed to send zapiex API request. ' . $url . ' (HTTP code : ' . $httpCode . '). (error code : ' . $errorCode . '). (error message : ' . $error . ').');
            }

            $result = json_decode($jsonResult, true, self::JSON_DECODE_DEFAULT_DEPTH, JSON_THROW_ON_ERROR);

            if ($httpMethod == self::HTTP_POST) {
                // All zapiex POST request to API endpoints, in case of failure it return the errors in following format: https://docs.zapiex.com/v3/#tag/Errors
                if ($result['statusCode'] != 200) {
                    Mage::throwException(
                        'Failed to send zapiex API request: ' . $url . '. ' .
                        '(Status code : ' . $result['statusCode'] . '). ' .
                        '(Error type : ' . $result['errorType'] . '). ' .
                        '(Error message : ' . $result['errorMessage'] . '). ' .
                        '(Request ID : ' . $result['requestId'] . ').'
                    );
                }
            }

            return array('response' => $result);


        } catch (Throwable $t) {
            Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex.log", true);
            Mage::logException($t);
            Mage::throwException($t->getMessage());
        }
    }
}
