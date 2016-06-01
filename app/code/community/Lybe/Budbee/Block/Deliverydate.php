<?php

class Lybe_budbee_Block_Deliverydate extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('lybe/budbee/sales/view_delivery_date.phtml');
    }

    public function displayDeliveryDate()
    {
        $displayDate = false;

        if($this->getOrder()->getBudbeeDesiredDeliveryDate() != NULL)
            $displayDate = true;

        return $displayDate;
    }

    public function getDisplayDeliveryDate()
    {
        $info = array(
            'delivered_date' => $this->getOrder()->getBudbeeDesiredDeliveryDate(),
            'door_code' => $this->getOrder()->getBudbeeDoorCode(),
            'outside_door' => $this->getOrder()->getBudbeeOutsideDoor()
        );

        return $info;
    }

    public function getOrder()
    {
        return Mage::registry('current_order');
    }
}