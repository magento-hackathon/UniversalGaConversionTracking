<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */
class Interactiv4_GAConversionTrack_Model_System_Config_Order_Status
{
    public function toOptionArray()
    {
        $options = Mage::getResourceModel('sales/order_status_collection')->toOptionArray();
        return $options;
    }
}