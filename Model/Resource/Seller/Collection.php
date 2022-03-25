<?php

class Aiti_Zapiex_Model_Resource_Seller_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() {
        $this->_init('aiti_zapiex/seller');
    }

    public function insertAliexpressSellerData(array $data) {
        Mage::getModel('aiti_zapiex/seller')
            ->load($data['store_id'], 'store_id')
            ->setStoreId($data['store_id'])
            ->setStoreName($data['store_name'])
            ->setStoreUrl($data['store_url'])
            ->setSellerId($data['seller_id'])
            ->setCompanyId($data['company_id'])
            ->setSellerDetails($data['details'])
            ->save();
    }
}