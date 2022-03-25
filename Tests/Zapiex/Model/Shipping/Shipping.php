<?php

use Tester\Assert;

/**
 * @testCase
 */
class ShippingTest extends Aiti_Testing_Model_TestCase {

    private const _TEST_ALIEXPRESS_PROD_ID = 1234;
    private const _TEST_SHIPPING = 2.99;

    private $_storeId = null;

    private function _getConnection() {
        return \Mage::getSingleton('core/resource')->getConnection('core_write');
    }

    protected function setUp() {
        parent::setUp();

        $this->_getConnection()->beginTransaction();

        $this->_storeId = Mage::app()->getAnyStoreView()->getId();

        Mage::getResourceSingleton('aiti_zapiex/shipping')->massCleanAndInsertNew(
            (string) self::_TEST_ALIEXPRESS_PROD_ID,
            array(
                array(
                    'product_id' => self::_TEST_ALIEXPRESS_PROD_ID,
                    'store_id' => $this->_storeId,
                    'country_code' => 'ASD',
                    'shipping' => self::_TEST_SHIPPING,
                )
            )
        );
    }

    public function testPrepareProductShippingPrices() {

        $import = Mage::getModel('aiti_marketplace/imported');

        $import->setAliexpressProductId(self::_TEST_ALIEXPRESS_PROD_ID);
        $import->setCurrency('USD');

        $merchant = Mage::getModel('aiti_merchant/merchant');
        $merchant->setData('currency', 'USD');

        $result = Mage::helper('aiti_marketplace/feed')->prepareProductShippingPrices($import, $merchant);

        Assert::count(1, $result);
        Assert::hasKey($this->_storeId, $result);
        Assert::true((float)$result[$this->_storeId] === self::_TEST_SHIPPING);
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->_getConnection()->rollBack();
    }

}
