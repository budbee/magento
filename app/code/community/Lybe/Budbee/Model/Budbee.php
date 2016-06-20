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
class Lybe_Budbee_Model_Budbee  extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Carrier's code, as defined in parent class
     *
     * @var string
     */
    protected $_code = Lybe_Budbee_Helper_Data::BUDBEE_SHIPPING_CODE;

    /**
     * load Lib/Budbee
     */
    public function __construct()
    {
        require_once(Mage::getBaseDir('lib') . '/Budbee/vendor/autoload.php');
    }

    /**
     * Setup Api connexion
     * @return \Budbee\Client
     */
    public function setupApi()
    {
        $apiKey = $this->getBudbeeApiKey();
        $apiSecret = $this->getBudbeeApiSecretKey();
        $sandbox = $this->getSandBoxMode();
        $client = new \Budbee\Client($apiKey, $apiSecret, $sandbox);
        return $client;
    }

    protected function _getHelper()
    {
        return Mage::helper('lybe_budbee');
    }

    /**
     * Call Budbee API to validate the postal code which determines the visibility of budbee shipping method
     *
     * @return bool
     */
    public function showBudbeeAsShippingMethod($hidden = false)
    {
        $client = $this->setupApi();
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $postalCode = $cart->getShippingAddress()->getPostcode();

        if ($client){
            $postalCodesAPI = new \Budbee\PostalcodesApi($client);
            try {
                $possibleCollectionPoints = $postalCodesAPI->checkPostalCode($postalCode);
                if (count ($possibleCollectionPoints)){
                    return true;
                }
            } catch (\Budbee\Exception\BudbeeException $e) {
                //echo $e->getMessage(). "<br>"; write in log
                return false;
            }
        }

        return false;
    }

    /**
     * Returns available shipping rates for Budbee
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->_getHelper()->isEnabled())  return false;

        $is_Shippable = $this->_getHelper()->_isShippable($request->getAllItems());

        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');


        // check if there is date interval returned
        $validInterval = $this->getBudbeeIntervals();

        if ($is_Shippable && $this->showBudbeeAsShippingMethod() && $validInterval)
            $result->append($this->_getExpressRate());

        return $result;
    }
    /**
     * Returns Allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return array(
            'express'     =>  $this->getConfigData('title'),
        );
    }

    /**
     * Get Express rate object
     *
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getExpressRate()
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('express');
        $rate->setMethodTitle('Express delivery');
        $rate->setPrice($this->_getHelper()->getPrice());
        $rate->setCost(0);
        return $rate;
    }

    public function getSandBoxMode($development = false)
    {
        return $this->_getHelper()->getSandBoxMode($development);
    }

    public function getBudbeeApiKey()
    {
        return $this->_getHelper()->getBudbeeApiKey();
    }

    public function getBudbeeApiSecretKey()
    {
        return $this->_getHelper()->getBudbeeApiSecretKey();
    }

    /**
     * Call Budbee Api to get available intervals
     * @return array
     */
    public function getBudbeeIntervals()
    {
        $client = $this->setupApi();
        $intervalAPI = new \Budbee\IntervalApi($client);
        $cart = Mage::getModel('checkout/cart')->getQuote();
        $postalCode = $cart->getShippingAddress()->getPostcode();
        /*
         * check if interval by Number is enabled
         * n always goes first
         */
        if ($this->_getHelper()->isIntervalByNumber() == true) {
            $intervalResponse = $intervalAPI->getIntervals($postalCode, $this->_getHelper()->getIntervalByNumber());
        }elseif  ($this->_getHelper()->isIntervalByDate() == true) {

            $now = Mage::getModel('core/date')->gmtTimestamp();
            $fromDate =  $now + (3600 * 24) * intval( $this->_getHelper()->getStartIntervalDate());
            $toDate = $fromDate + (3600 * 24) * intval( $this->_getHelper()->getIntervalDateValue());
            $intervalResponse = $intervalAPI->getIntervalsFromToDate($postalCode, date('Y-m-d',$fromDate), date('Y-m-d',$toDate));

        }else{
            // if there is no setting in backendget interval by 2 by default
            $intervalResponse = $intervalAPI->getIntervals($postalCode, 2);
        }


        return $intervalResponse;
    }

    /**
     * Call Budbee Order API
     * @return \Budbee\OrderApi
     */
    public function getOrderApi()
    {
        $client = $this->setupApi();
        return  new \Budbee\OrderApi($client);
    }

}