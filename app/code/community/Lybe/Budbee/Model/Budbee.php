<?php
class Lybe_Budbee_Model_Budbee  extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    /**
     * Carrier's code, as defined in parent class
     *
     * @var string
     */
    protected $_code = Lybe_Budbee_Helper_Data::BUDBEE_SHIPPING_CODE;


    protected function _getHelper()
    {
        return Mage::helper('lybe_budbee');
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

        if ($is_Shippable)
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
}