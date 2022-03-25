<?php
 
class Aiti_Zapiex_Model_Resource_Shipping extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_isPkAutoIncrement = false;
        $this->_init('aiti_zapiex/shipping_prices', null);
        $this->_idFieldName = null; //magento cannot handle composite keys
    }

    public function massCleanAndInsertNew(string $productId, array $data) {
        $resource = Mage::getSingleton('core/resource');
        $db = $resource->getConnection("core/write");

        try {
            $db->beginTransaction();

            $productIdsFromData = array_unique(array_column($data, 'product_id'));
            $idFromData = reset($productIdsFromData);

            // clean
            if (!empty($productId) && (string) $idFromData === $productId) {
                $db->delete($this->getMainTable(), $db->quoteInto('product_id = ?', $productId));
            }

            // insert new fresh values
            foreach ($data as $value) {
                $db->insertOnDuplicate(
                    $resource->getTableName('aiti_zapiex/shipping_prices'),
                    $value
                );
            }

            $db->commit();

        } catch (Exception $e) {
            $db->rollback();
            Mage::throwException($e->getMessage());
        }
    }

}
