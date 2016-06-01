<?php

class Lybe_budbee_Model_Checkout_Onepage extends Mage_Checkout_Model_Type_Onepage
{
    public function saveBilling($data, $customerAddressId)
    {
        Mage::log($this->getQuote()->getShippingAddress()->getPostcode(), null ,'shipping_postalcode.log');
        Mage::log($this->getQuote()->getBillingAddress()->getPostcode(), null ,'billing_postalcode.log');

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