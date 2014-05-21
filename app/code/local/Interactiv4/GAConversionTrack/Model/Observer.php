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
        $statusesToTrack = Mage::helper('i4gaconversiontrack')->getStatusesToTrack($order->getStoreId());

        if (Mage::helper('i4gaconversiontrack')->isAvailable($order->getStoreId())
            && !$order->getData('i4gaconversiontrack_tracked')
            & in_array($order->getStatus(), $statusesToTrack)
        ) {
            $store = Mage::app()->getStore($order->getStoreId());
            $googleAnalyticsAccountId = Mage::helper('i4gaconversiontrack')->getGoogleAnalyticsAccountId($order->getStoreId());
            $domain = parse_url($store->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB), PHP_URL_HOST);
            $ga_tracking = new Interactiv4_GAConversionTrack_Model_Tracking(
                $googleAnalyticsAccountId,
                $domain,
                $order->getData('i4gaconversiontrack_user_agent')
            );
            $ga_tracking->setData('utmip', $order->getRemoteIp());
            $ga_tracking->setData('utmsr', $order->getData('i4gaconversiontrack_screen_resolution'));
            $ga_tracking->setData('utmsc', $order->getData('i4gaconversiontrack_screen_color_depth'));
            $ga_tracking->setData('utmul', $order->getData('i4gaconversiontrack_browser_language'));
            $ga_tracking->setData('utmje', $order->getData('i4gaconversiontrack_browser_java_enabled'));

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
        $request = Mage::app()->getRequest();
        $order->setData('i4gaconversiontrack_user_agent', $request->getParam('i4gaconversiontrack_user_agent'));
        $order->setData('i4gaconversiontrack_screen_resolution', $request->getParam('i4gaconversiontrack_screen_resolution'));
        $order->setData('i4gaconversiontrack_screen_color_depth', $request->getParam('i4gaconversiontrack_screen_color_depth'));
        $order->setData('i4gaconversiontrack_browser_language', $request->getParam('i4gaconversiontrack_browser_language'));
        $order->setData('i4gaconversiontrack_browser_java_enabled', $request->getParam('i4gaconversiontrack_browser_java_enabled'));
        $order->save();
    }
}
