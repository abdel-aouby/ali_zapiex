<?php
 
class Aiti_Zapiex_Model_Resource_Category extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_isPkAutoIncrement = false;
        $this->_init('aiti_zapiex/category', 'category_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object) {
        $object->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($object);
    }

    /**
     * Remove categories
     */
    public function cleanCategoriesTable() {
        $db = $this->_getConnection("core/write");

        do {
            $records = $db->query('DELETE FROM ' . $this->getMainTable() . ' limit 1000 ');
        } while($records->rowCount());
    }

}