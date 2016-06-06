<?php
class Lybe_Budbee_Model_Sales_Order extends Mage_Sales_Model_Order
{
    public function getBudbeeDesiredDeliveryFormatedDate()
    {
        return Mage::helper('lybe_budbee')->formatDesiredDeliveryDate($this->getBudbeeDesiredDeliveryDate());
    }

    public function getBudbeeOutsideDoorformated()
    {
        return ($this->getOutsideDoor() == 1) ? $this->__('Yes') : $this->__('No');
    }
}