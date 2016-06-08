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

        Mage::log($postData, null , 'post.log');

        if(isset($postData))
        {
            $deliveryDate = (empty($postData['budbee_delivery_date'])) ? NULL : $postData['budbee_delivery_date'];
            $observer->getQuote()->getShippingAddress()->setbudbeeDesiredDeliveryDate($deliveryDate);

            $doorCode = (empty($postData['budbee_door_code'])) ? NULL : $postData['budbee_door_code'];
            $observer->getQuote()->getShippingAddress()->setBudbeeDoorCode($doorCode);

            $outsideDoor = (empty($postData['budbee_outside_door'])) ? NULL : $postData['budbee_outside_door'];
            $observer->getQuote()->getShippingAddress()->setBudbeeOutsideDoor($outsideDoor);

            $additionalInfo = (empty($postData['budbee_additional_info'])) ? NULL : $postData['budbee_additional_info'];

            Mage::log($additionalInfo , null , 'additional.log');

            $observer->getQuote()->getShippingAddress()->setBudbeeAdditionalInfo($additionalInfo);

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
        $observer->getQuote()->getShippingAddress()->setBudbeeAdditionalInfo($observer->getOrder()->getBudbeeAdditionalInfo());
    }

    /**
     * Export magento Order and send it to Budbee
     * @param $observer
     */
    public function createBudbeeOrder($observer)
    {
        $orderData = $observer->getOrder();

        if($orderData->getShippingMethod() == Lybe_Budbee_Helper_Data::BUDBEE_SHIPPING_METHOD){
            $model = Mage::getModel('lybe_budbee/budbee');
            $intervalResponse = $model->getBudbeeIntervals();

            $chosenInterval = array();

            foreach ($intervalResponse as $key => $interval) {
                $value = strtotime($interval->delivery->start->format('Y-m-d H:i')). ":"
                    .strtotime($interval->delivery->stop->format('Y-m-d H:i')). ":"
                    .strtotime($interval->collection->start->format('Y-m-d H:i')). ":"
                    .strtotime($interval->collection->stop->format('Y-m-d H:i'));

                if ($value == $orderData->getBudbeeDesiredDeliveryDate()){
                    $chosenInterval = $interval;
                }
            }


            if (count($chosenInterval)){
                $interval = new \Budbee\Model\OrderInterval($chosenInterval->collection, $chosenInterval->delivery);
                $collectionPointId = $chosenInterval->collectionPointIds;
            }


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
                $article->unitPrice =  str_replace('.','',$item->getPrice());
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

            $shipping_address = $orderData->getShippingAddress()->getStreet();

            $deliveryAddress = new \Budbee\Model\Address();
            $deliveryAddress->street = $shipping_address[0];
            $deliveryAddress->street2 = $shipping_address[1];
            $deliveryAddress->postalCode = $orderData->getShippingAddress()->getPostcode();
            $deliveryAddress->city = $orderData->getShippingAddress()->getCity();
            $deliveryAddress->country = $orderData->getShippingAddress()->getCountry();

            $deliveryContact->address = $deliveryAddress;

            $order->delivery = $deliveryContact;

            $order->delivery->doorCode = $orderData->getBudbeeDoorCode();
            $order->delivery->outsideDoor = ($orderData->getBudbeeOutsideDoor()) ? true : false;
            $order->delivery->additionalInfo = $orderData->getBudbeeAdditionalInfo();

            try{
                $createdOrder = $orderAPI->createOrder($order);
                // to be added in budbee_debug.log
                 //Mage::log(json_encode($order),null , 'budbeeorder.log');
            }catch(Exception $e){
                die("cannot create an order in Budbee ". $e->getMessage());
            }
        }
    }
}