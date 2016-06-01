<?php
class Lybe_Budbee_Block_Budbee extends Mage_Checkout_Block_Onepage_Abstract
{
    public function __construct(){
        $this->setTemplate('lybe/budbee/budbee.phtml');
    }

    // call budbee api to get intervals
    public function getPostUrl()
    {
        return Mage::getUrl('budbee/index/ajax', array('_secure'=>true));
    }
}