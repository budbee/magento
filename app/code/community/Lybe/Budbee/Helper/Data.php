<?php
class Lybe_Budbee_Helper_Data extends Mage_Core_Helper_Abstract
{
    const BUDBEE_SHIPPING_CODE = 'lybe_budbee';

    const BUDBEE_PRICE_XPATH = 'carriers/lybe_budbee/budbee_price';
    const BUDBEE_ACTIVE_XPATH  = 'carriers/lybe_budbee/active';
    const BUDBEE_API_USERNAME = 'carriers/lybe_budbee/budbee_username';
    const BUDBEE_API_PASSWORD = 'carriers/lybe_budbee/budbee_password';

    const CONFIGURABLE_PRODUCT_TYPE = 'configurable';
    const SIMPLE_PRODUCT_TYPE = 'simple';

    public function getPrice()
    {
        return Mage::getStoreConfig(self::BUDBEE_PRICE_XPATH);
    }

    public function isEnabled()
    {
        return Mage::getStoreConfig(self::BUDBEE_ACTIVE_XPATH);
    }

    public function _isShippable($items)
    {
        foreach($items as $item){

            if($item->getProduct()->getTypeId() == self::CONFIGURABLE_PRODUCT_TYPE){
               $_product = Mage::getModel('catalog/product')->load($item->getProductId());
                if(!$_product->getShipWithBudbee()) return false;

            }else if ($item->getProduct()->getTypeId() == self::SIMPLE_PRODUCT_TYPE){
                $_product = Mage::getModel('catalog/product')->load($item->getProductId());
                if(!$_product->getShipWithBudbee()) return false;
            }

        }
        return true;
    }
}