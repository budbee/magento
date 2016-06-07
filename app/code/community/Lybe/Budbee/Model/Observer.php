<?php
/**
 *  Copyright 2016 Lybe AB.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 *
 *  @package     Lybe_budbee
 *  @author      sabri.zouari@lybe.se
 */
class Lybe_Budbee_Model_Observer
{

    /**
     * Get budbee extra data from block and assign it to quote
     *
     * @param $observer
     * @return void
     */
    public function saveDesiredDeliveryTime($observer)
    {
        $postData = $observer->getRequest()->getPost();

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

    /**
     * In case of order Edit in adminhtml
     *
     * @todo not finished yet!
     * @param $observer
     */
    public function copyDesiredDeliveryTimeToQuote($observer)
    {
        $observer->getQuote()->getShippingAddress()->setBudbeeDesiredDeliveryDate($observer->getOrder()->getBudbeeDesiredDeliveryDate());
        $observer->getQuote()->getShippingAddress()->setBudbeeDoorCode($observer->getOrder()->getBudbeeDoorCode());
        $observer->getQuote()->getShippingAddress()->setBudbeeOutsideDoor($observer->getOrder()->getBudbeeOutsideDoor());
    }

    /**
     * Export magento Order and send it to Budbee
     * @param $observer
     */
    public function createBudbeeOrder($observer)
    {
        $orderData = $observer->getOrder();

        $model = Mage::getModel('lybe_budbee/budbee');
        $intervalResponse = $model->getBudbeeIntervals();

        $firstInterval = $intervalResponse[0];
        $interval = new \Budbee\Model\OrderInterval($firstInterval->collection, $firstInterval->delivery);
        $collectionPointId = $firstInterval->collectionPointIds;


        // Create Order Object
        $orderAPI = $model->getOrderApi();
        $order = new \Budbee\Model\OrderRequest();
        $order->interval = $interval;
        $order->collectionId = $collectionPointId[0];

        // Create Cart Object
        $cart = new \Budbee\Model\Cart();
        $cart->cartId = $orderData->getIncrementId();


        $budbeeItems = array();

        foreach ($orderData->getAllVisibleItems() as $item){

            $article = new \Budbee\Model\Article();
            $article->name = $item->getName();
            $article->reference = $item->getSku();
            $article->quantity = $item->getQtyOrdered();
            $article->unitPrice = intval($item->getPrice());
            $article->discountRate = 0;
            $article->taxRate = 10;
            array_push($budbeeItems, $article);
        }

        $cart->articles = $budbeeItems;


        $order->cart = $cart;

        // Specify Delivery information
        $deliveryContact = new \Budbee\Model\Contact();
        $deliveryContact->name = $orderData->getShippingAddress()->getName();
        $deliveryContact->referencePerson = $orderData->getShippingAddress()->getName();
        $deliveryContact->telephoneNumber = $orderData->getShippingAddress()->getTelephone();
        $deliveryContact->email = $orderData->getShippingAddress()->getEmail();
        $deliveryContact->doorCode = $orderData->getShippingAddress()->getBudbeeDoorCode();
        $deliveryContact->outsideDoor = $orderData->getShippingAddress()->getBudbeeOutsideDoor();

        $deliveryAddress = new \Budbee\Model\Address();
        $deliveryAddress->street = $orderData->getShippingAddress()->getStreet();
        $deliveryAddress->postalCode = $orderData->getShippingAddress()->getPostcode();
        $deliveryAddress->city = $orderData->getShippingAddress()->getCity();
        $deliveryAddress->country = $orderData->getShippingAddress()->getCountry();

        $deliveryContact->address = $deliveryAddress;

        $order->delivery = $deliveryContact;

        $order->delivery->doorCode = $orderData->getBudbeeDoorCode();
        $order->delivery->outsideDoor = ($orderData->getBudbeeOutsideDoor()) ? true : false;
        $order->delivery->additionalInfo = null;

        try{
            //$createdOrder = $orderAPI->createOrder($order);
            // to be added in budbee_debug.log
             Mage::log(json_encode($order),null , 'border.log');
        }catch(Exception $e){
            die("cannot create an order in Budbee ". $e->getMessage());
        }

    }
}