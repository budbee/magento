<?php
class Lybe_Budbee_IndexController extends Mage_Core_Controller_Front_Action
{
    public function AjaxAction()
    {
        // call api here..
        $shippingCode = $this->getRequest()->getPost('shipping_code');

        if(isset($shippingCode))
        {
            $dropdownBlock = $this->getLayout()->createBlock('lybe_budbee/onepage_deliverydate_dropdown');
            $dropdownBlock->setShippingCode($shippingCode);
            $content = $dropdownBlock->toHtml();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('dropdownhtml' => $content)));
        }
    }
}