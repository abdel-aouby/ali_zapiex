<?php

class Aiti_Zapiex_Model_Resource_Seller extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_isPkAutoIncrement = false;
        $this->_init('aiti_zapiex/seller', 'store_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($object);
    }
}