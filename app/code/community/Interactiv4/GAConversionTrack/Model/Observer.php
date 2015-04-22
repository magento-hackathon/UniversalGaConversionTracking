<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */
class Interactiv4_GAConversionTrack_Model_Observer
{
    public function track($observer) {
        $order = $observer->getEvent()->getOrder();

        if (Mage::helper('i4gaconversiontrack')->isAvailable($order->getStoreId())
            && !$order->getData('i4gaconversiontrack_tracked')
        ) {
            $statusesToTrack = Mage::helper('i4gaconversiontrack')->getStatusesToTrack($order->getStoreId());
            $pass = false;
            foreach($statusesToTrack as $statusToTrack) {
                if($order->getState() == $statusToTrack['state']
                    &&
                    $order->getStatus() == $statusToTrack['status']
                ) {
                    $pass = true; // if any of the state/status combination matches, pass it through
                }
            }
            if (!$pass) return;
            $store = Mage::app()->getStore($order->getStoreId());
            $googleAnalyticsAccountId = Mage::helper('i4gaconversiontrack')->getGoogleAnalyticsAccountId($order->getStoreId());
            $domain = parse_url($store->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), PHP_URL_HOST);
            $trackData =  $this->addTrackingDataToOrder($order);
            $ga_tracking = new Interactiv4_GAConversionTrack_Model_Tracking(
                $googleAnalyticsAccountId,
                $domain,
                $trackData->getData('i4gaconversiontrack_user_agent')
            );
            $ga_tracking->setData('uip', $order->getRemoteIp());
            $ga_tracking->setData('sr', $trackData->getData('i4gaconversiontrack_screen_resolution'));
            $ga_tracking->setData('sd', $trackData->getData('i4gaconversiontrack_screen_color_depth'));
            $ga_tracking->setData('ul', $trackData->getData('i4gaconversiontrack_browser_language'));
            $ga_tracking->setData('je', $trackData->getData('i4gaconversiontrack_browser_java_enabled'));
            $ga_tracking->setData('ua', $trackData->getData('i4gaconversiontrack_user_agent'));

            $ga_tracking->pageView(Mage::getStoreConfig('i4gaconversiontrack/general/page_title', $store), Mage::getStoreConfig('i4gaconversiontrack/general/page_url', $store));

            $address = $order->getIsVirtual() ? $order->getBillingAddress() : $order->getShippingAddress();

            $ga_tracking->addTransaction(
                $order->getIncrementId(),
                $order->getBaseGrandTotal(),
                Mage::helper('core')->jsQuoteEscape(Mage::app()->getStore()->getFrontendName()),
                $order->getBaseTaxAmount(),
                $order->getBaseShippingAmount(),
                Mage::helper('core')->jsQuoteEscape(Mage::helper('core')->escapeHtml($address->getCity())),
                Mage::helper('core')->jsQuoteEscape(Mage::helper('core')->escapeHtml($address->getRegion())),
                Mage::helper('core')->jsQuoteEscape(Mage::helper('core')->escapeHtml($address->getCountry()))
            );

            foreach ($order->getAllVisibleItems() as $item) {
                $ga_tracking->addItem(
                    $order->getIncrementId(),
                    Mage::helper('core')->jsQuoteEscape($item->getSku()),
                    $item->getBasePrice(),
                    $item->getQtyOrdered(),
                    Mage::helper('core')->jsQuoteEscape($item->getName())
                );
            }

            $order->setData('i4gaconversiontrack_tracked', 1);

            $comment = "GA Conversion Track OK"
                . "<br />GA Code: " . $googleAnalyticsAccountId
                . "<br />Domain: " . $domain
                . "<br />Order #: " . $order->getIncrementId()
                . "<br />Amount: " . Mage::app()->getLocale()->currency($order->getOrderCurrencyCode())->toCurrency($order->getBaseGrandTotal());
            $order->addStatusHistoryComment($comment);

            $order->save();
        }
    }


    public function saveFields($observer) {
        $order = $observer->getEvent()->getOrder();
        $this->addTrackingDataToOrder($order);
    }

    /**
     * Get tracking data from order or request
     * @param Mage_Sales_Model_Order $order
     * @return Varien_Object
     */
    protected function addTrackingDataToOrder($order) {
        $trackedData = unserialize($order->getData('i4gaconversiontrack_track_data'));
        if ($trackedData === false) {
            $trackedData = $this->extractTrackDataFromRequest();
            $order->setData('i4gaconversiontrack_track_data', serialize($trackedData));
        }
        return $trackedData;
    }

    /**
     * @return Varien_Object
     */
    protected function extractTrackDataFromRequest() {
        $request = Mage::app()->getRequest();
        /* Set data in serialized object in the order to allow easy adding of new attributes */
        $trackData = new Varien_Object();
        $trackData->setData('i4gaconversiontrack_user_agent', $request->getParam('i4gaconversiontrack_user_agent'));
        $trackData->setData('i4gaconversiontrack_screen_resolution', $request->getParam('i4gaconversiontrack_screen_resolution'));
        $trackData->setData('i4gaconversiontrack_screen_color_depth', $request->getParam('i4gaconversiontrack_screen_color_depth'));
        $trackData->setData('i4gaconversiontrack_browser_language', $request->getParam('i4gaconversiontrack_browser_language'));
        $trackData->setData('i4gaconversiontrack_browser_java_enabled', $request->getParam('i4gaconversiontrack_browser_java_enabled'));
        return $trackData;
    }

    /**
     * Add order information into GA block to render on checkout success pages
     *
     * @param Varien_Event_Observer $observer
     */
    public function setGoogleAnalyticsOnOrderSuccessPageView(Varien_Event_Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return;
        }
        $block = Mage::app()->getFrontController()->getAction()->getLayout()->getBlock('google_analytics_universal');
        if ($block) {
            $block->setOrderIds($orderIds);
        }
    }
}
