<?php
 
class Aiti_Zapiex_Model_Resource_Shipping_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() {
        $this->_init('aiti_zapiex/shipping');
    }

    public function getIdFieldName() {
        throw new Exception('Magento cannot handle composite primary key!');
    }

}