<?php
class Lybe_Budbee_Helper_Data extends Mage_Core_Helper_Abstract
{
    const BUDBEE_SHIPPING_CODE = 'lybe_budbee';

    const BUDBEE_PRICE_XPATH = 'carriers/lybe_budbee/budbee_price';
    const BUDBEE_ACTIVE_XPATH  = 'carriers/lybe_budbee/active';
    const BUDBEE_API_KEY = 'carriers/lybe_budbee/budbee_username';
    const BUDBEE_API_SECRET_KEY = 'carriers/lybe_budbee/budbee_password';

    const CONFIGURABLE_PRODUCT_TYPE = 'configurable';
    const SIMPLE_PRODUCT_TYPE = 'simple';

    const BUDBEE_LIVE_URL    = 'https://api.budbee.com'; // Production url
    const BUDBEE_SANDBOX_URL = 'http://sandbox.api.budbee.com'; // Sandbox url
    const BUDBEE_LOCALHOST_URL = "http://localhost:9300"; // Internal development

    public function getPrice($store = null)
    {
        return Mage::getStoreConfig(self::BUDBEE_PRICE_XPATH , $store);
    }

    public function isEnabled($store = null)
    {
        return Mage::getStoreConfig(self::BUDBEE_ACTIVE_XPATH, $store);
    }

    public function getBudbeeApiKey($store = null)
    {
        return Mage::getStoreConfig(self::BUDBEE_API_KEY, $store);
    }

    public function getBudbeeApiSecretKey($store = null)
    {
        return Mage::getStoreConfig(self::BUDBEE_API_SECRET_KEY, $store);
    }

    public function getSandBoxMode($development = false, $store = null)
    {
        $res = SELF::BUDBEE_LIVE_URL;
        if (Mage::getStoreConfig('carriers/lybe_budbee/budbee_sandbox', $store)) {
            $res = SELF::BUDBEE_SANDBOX_URL;
        }

        if ($development)
            $res = SELF::BUDBEE_LOCALHOST_URL;

        return $res;
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

    public function formatDesiredDeliveryDate($string){
        $date =  explode(":",$string);
        $start_date = date('Y-m-d H:i', $date[0]);
        $end_date = date('H:i', $date[1]);

        return $start_date. " - ".$end_date;
    }
}