<?php
 
class Aiti_Zapiex_Model_Resource_Category_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected function _construct() {
        $this->_init('aiti_zapiex/category');
    }

    public function toOptionArray() {
        return $this->setOrder('category_path', Varien_Data_Collection::SORT_ORDER_ASC)->_toOptionArray('category_id', 'category_path');
    }

}