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

class Lybe_budbee_Model_Checkout_Onepage extends Mage_Checkout_Model_Type_Onepage
{

    /**
     * add Extra Budbee data in Billing/Shipping address
     *
     * @param array $data
     * @param int $customerAddressId
     * @return Mage_Checkout_Model_Type_Onepage
     * @throws Exception
     */
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