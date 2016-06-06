<?php

class Lybe_budbee_Model_Checkout_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    public function saveBilling($data, $customerAddressId)
    {
        $desiredDeliveryDate = $this->getQuote()->getShippingAddress()->getBudbeeDesiredDeliveryDate();
        $doorCode = $this->getQuote()->getShippingAddress()->getBudbeeDoorCode();
        $outsideDoor = $this->getQuote()->getShippingAddress()->getBudbeeOutsideDoor();

        $returnValue = parent::saveBilling($data, $customerAddressId);

        $this->getQuote()->getShippingAddress()->setBudbeeDesiredDeliveryDate($desiredDeliveryDate);
        $this->getQuote()->getShippingAddress()->setBudbeeDoorCode($doorCode);
        $this->getQuote()->getShippingAddress()->setBudbeeOutsideDoor($outsideDoor);

        $this->getQuote()->getShippingAddress()->save();

        return $returnValue;
    }
}