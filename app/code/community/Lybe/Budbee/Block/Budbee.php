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
class Lybe_Budbee_Block_Budbee extends Mage_Checkout_Block_Onepage_Abstract
{
    public function __construct(){
        $this->setTemplate('lybe/budbee/budbee.phtml');
    }

    /**
     * Call Budbee api get intervals
     * @return mixed
     */
    public function getBudbeeIntervals()
    {
        $model = Mage::getModel('lybe_budbee/budbee');
        return $model->getBudbeeIntervals();
    }

    public function isOutSideDoor()
    {
        return Mage::getStoreConfig(Lybe_Budbee_Helper_Data::BUDBEE_OUTSIDE_DOOR);
    }
}