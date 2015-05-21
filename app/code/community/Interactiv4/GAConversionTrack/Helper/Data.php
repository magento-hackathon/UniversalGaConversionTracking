<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */
class Interactiv4_GAConversionTrack_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_ACTIVE = 'i4gaconversiontrack/general/active';
    const XML_PATH_ORDER_STATUS = 'i4gaconversiontrack/general/order_status';
    const XML_PATH_ACCOUNT = 'google/analytics/account';

    public function isAvailable($store = null) {
        $isActive = Mage::getStoreConfigFlag(self::XML_PATH_ACTIVE, $store);
        $isGoogleAnalyticsActive = Mage::getStoreConfigFlag(Mage_GoogleAnalytics_Helper_Data::XML_PATH_ACTIVE, $store);
        $googleAnalyticsAccountId = self::getGoogleAnalyticsAccountId($store);
        return $isActive && $isGoogleAnalyticsActive && $googleAnalyticsAccountId;
    }

    public function getGoogleAnalyticsAccountId($store = null){
        return Mage::getStoreConfig(Mage_GoogleAnalytics_Helper_Data::XML_PATH_ACCOUNT, $store);
    }

    public function getStatusesToTrack($store = null){
        $statusesToTrack = unserialize(Mage::getStoreConfig(self::XML_PATH_ORDER_STATUS, $store));
        return $statusesToTrack;
    }

    public function getAccount($store = null)
    {
        return Mage::getStoreConfig(Interactiv4_GAConversionTrack_Helper_Data::XML_PATH_ACCOUNT, $store);
    }
}
