<?php

class Lybe_Budbee_Model_Observer
{
    public function saveDesiredDeliveryTime($observer)
    {
        $postData = $observer->getRequest()->getPost();

        Mage::log($postData, null , 'data.log');

        if(isset($postData))
        {
            $deliveryDate = (empty($postData['budbee_delivery_date'])) ? NULL : $postData['budbee_delivery_date'];
            $observer->getQuote()->getShippingAddress()->setbudbeeDesiredDeliveryDate($deliveryDate);

            $doorCode = (empty($postData['budbee_door_code'])) ? NULL : $postData['budbee_door_code'];
            $observer->getQuote()->getShippingAddress()->setBudbeeDoorCode($doorCode);

            $outsideDoor = (empty($postData['budbee_outside_door'])) ? NULL : $postData['budbee_outside_door'];
            $observer->getQuote()->getShippingAddress()->setBudbeeOutsideDoor($outsideDoor);

        }
    }

    public function copyDesiredDeliveryTimeToQuote($observer)
    {
        $observer->getQuote()->getShippingAddress()->setBudbeeDesiredDeliveryDate($observer->getOrder()->getBudbeeDesiredDeliveryDate());
        $observer->getQuote()->getShippingAddress()->setBudbeeDoorCode($observer->getOrder()->getBudbeeDoorCode());
        $observer->getQuote()->getShippingAddress()->setBudbeeOutsideDoor($observer->getOrder()->getBudbeeOutsideDoor());
    }
}