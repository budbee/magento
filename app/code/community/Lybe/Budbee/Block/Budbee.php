<?php
class Lybe_Budbee_Block_Budbee extends Mage_Checkout_Block_Onepage_Abstract
{
    public function __construct(){
        $this->setTemplate('lybe/budbee/budbee.phtml');
    }

    // call budbee api to get intervals
    public function getBudbeeIntervals()
    {
        $model = Mage::getModel('lybe_budbee/budbee');
        $client = $model->setupApi();
        $intervalAPI = new \Budbee\IntervalApi($client);
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $postalCode = $cart->getShippingAddress()->getPostcode();
        $intervalResponse = $intervalAPI->getIntervals($postalCode, 2);

        return $intervalResponse;
    }
}