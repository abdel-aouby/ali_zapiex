<?php

class Aiti_Zapiex_Model_Cron {

    /**
     * Method to update aliexpress categories in the table [aiti_aliexpress_category]
     */
    public function updateAliexpressCategories() {
        $autoRefresh = Mage::getStoreConfig('aiti_zapiex/api_category_endpoint/categories_update_cron_active');

        if ($autoRefresh) {
            $categoryApiResponse = Mage::getModel('aiti_zapiex/api_category')->getAliexpressCategories();
            if (isset($categoryApiResponse['response']['searchCategories'])) {
                $productCategories = $categoryApiResponse['response']['searchCategories'];

                if (!empty($productCategories)) {
                    $resourceModel = Mage::getResourceSingleton('aiti_zapiex/category');
                    $resourceModel->beginTransaction();
                    try {
                        Mage::getResourceSingleton('aiti_zapiex/category')->cleanCategoriesTable();

                        $productCategories = Mage::helper('aiti_zapiex')->sortProductCategories($productCategories);
                        $paths = Mage::helper('aiti_zapiex')->generateCategoriesPath($productCategories);

                        foreach ($productCategories as $value) {
                            Mage::getModel('aiti_zapiex/category')
                                ->load($value['id'], 'category_id')
                                ->setCategoryId($value['id'])
                                ->setParentId($value['parentId'] == 0 ? null : $value['parentId'])
                                ->setName($value['enName'])
                                ->setCategoryPath($paths[$value['id']])
                                ->setLevel($value['level'])
                                ->setIsLeaf($value['isLeaf'])
                                ->save();
                        }

                        $resourceModel->commit();
                    } catch (Throwable $t) {
                        $resourceModel->rollBack();
                        Mage::log($t->getMessage(), Zend_Log::ERR, "aiti_zapiex.log", true);
                        Mage::log($t->getTraceAsString(), Zend_Log::ERR, "aiti_zapiex.log", true);
                        Mage::logException($t);
                    }
                }
            }
        }
    }
}
