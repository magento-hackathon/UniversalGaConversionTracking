<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */

/* https://developers.google.com/analytics/resources/concepts/gaConceptsTrackingOverview */

class Interactiv4_GAConversionTrack_Model_Tracking extends Varien_Object
{
    const GA_URL = 'http://www.google-analytics.com';
    const GA_BEACON = '/__utm.gif?';

    const REQUEST_TYPE_PAGE = 'page';
    const REQUEST_TYPE_EVENT = 'event';
    const REQUEST_TYPE_TRANSACTION = 'tran';
    const REQUEST_TYPE_ITEM = 'item';

    const SCOPE_VISITOR = 1;
    const SCOPE_SESSION = 2;
    const SCOPE_PAGE = 3;

    protected $_custom_vars;

    /**
     * Create Google Analytics Tracking
     */
    public function __construct($ga_account, $domain, $agent = 'GA Agent') {
        $init_data = array(
            'utmwv'     => '4.4sh',
            'utmcs'     => 'UTF-8',
            'utmul'     => 'en-us',

            'utmn'      => $this->getRandomId(),
            'utmhid'    => $this->getRandomId(),

            'utmac'     => $ga_account,
            'utmhn'     => $domain,

            'user_agent' => $agent
        );

        parent::__construct($init_data);
    }

    public function getCustomVars(){
        if (is_null($this->_custom_vars)) {
            $this->_custom_vars = new Varien_Data_Collection();
        }
        return $this->_custom_vars;
    }

    public function pageView($title, $page, $utmhid = null) {
        $params = array(
            'utmwv'     => $this->getData('utmwv'),
            'utmn'      => $this->getData('utmn'),
            'utmhn'     => $this->getData('utmhn'),
            'utmcs'     => $this->getData('utmcs'),
            'utmje'     => $this->getData('utmje'),
            'utmsc'     => $this->getData('utmsc'),
            'utmsr'     => $this->getData('utmsr'),
            'utmul'     => $this->getData('utmul'),
            'utmdt'     => $title,
            'utmhid'    => !is_null($utmhid) ? $utmhid : $this->getRandomId(),
            'utmp'      => $page,
            'utmac'     => $this->getData('utmac'),
            'utmcc'     => $this->getData('utmcc') ? $this->getData('utmcc') : $this->getCookieParams(),
            'utmr'      => $this->getData('utmr'),
            'utmip'     => $this->getData('utmip')
        );

        return $this->request($params);
    }


    /**
     * Add Transaction
     * @return String
     */
    public function addTransaction($order_id, $total, $store_name = null, $tax = null, $shipping = null, $city = null, $region = null, $country = null, $utmhid = null) {
        $params = array(
            'utmwv'     => $this->getData('utmwv'),
            'utmn'      => $this->getData('utmn'),
            'utmhn'     => $this->getData('utmhn'),
            'utmt'      => self::REQUEST_TYPE_TRANSACTION,
            'utmcs'     => $this->getData('utmcs'),
            'utmje'     => $this->getData('utmje'),
            'utmsc'     => $this->getData('utmsc'),
            'utmsr'     => $this->getData('utmsr'),
            'utmul'     => $this->getData('utmul'),
            'utmhid'    => !is_null($utmhid) ? $utmhid : $this->getRandomId(),
            'utmac'     => $this->getData('utmac'),
            'utmcc'     => $this->getData('utmcc') ? $this->getData('utmcc') : $this->getCookieParams(),
            'utmtid'    => $order_id,
            'utmtst'    => $store_name,
            'utmtto'    => $total,
            'utmttx'    => $tax,
            'utmtsp'    => $shipping,
            'utmtci'    => $city,
            'utmtrg'    => $region,
            'utmtco'    => $country,
            'utmr'      => $this->getData('utmr'),
            'utmip'     => $this->getData('utmip')
        );

        return $this->request($params);
    }

    /**
     * Add Item to Transaction
     * @return String
     */
    public function addItem($order_id, $sku, $price, $quantity, $name = null, $category = null, $utmhid = null) {
        $params = array(
            'utmwv'     => $this->getData('utmwv'),
            'utmn'      => $this->getData('utmn'),
            'utmhn'     => $this->getData('utmhn'),
            'utmt'      => self::REQUEST_TYPE_ITEM,
            'utmcs'     => $this->getData('utmcs'),
            'utmje'     => $this->getData('utmje'),
            'utmsc'     => $this->getData('utmsc'),
            'utmsr'     => $this->getData('utmsr'),
            'utmul'     => $this->getData('utmul'),
            'utmhid'    => !is_null($utmhid) ? $utmhid : $this->getRandomId(),
            'utmac'     => $this->getData('utmac'),
            'utmcc'     => $this->getData('utmcc') ? $this->getData('utmcc') : $this->getCookieParams(),
            'utmtid'    => $order_id,
            'utmipc'    => $sku,
            'utmipn'    => $name,
            'utmiva'    => $category,
            'utmipr'    => $price,
            'utmiqt'    => $quantity,
            'utmr'      => $this->getData('utmr'),
            'utmip'     => $this->getData('utmip')
        );

        return $this->request($params);
    }

    /**
     * Add Custom Var
     * @return Varien_Object
     */
    public function addCustomVar($name, $value, $scope) {
        $item = new Varien_Object();
        $item->setName($name);
        $item->setValue($value);
        $item->setScope($scope);
        return $this->getColllection()->addItem($item);
    }

    /**
     * Add Custom Visitor Var
     * @return Varien_Object
     */
    public function addVisitorVar($name, $value) {
        return $this->addCustomVar($name, $value, self::SCOPE_VISITOR);
    }

    /**
     * Add Custom Visitor Var
     * @return Varien_Object
     */
    public function addSessionVar($name, $value) {
        return $this->addCustomVar($name, $value, self::SCOPE_SESSION);
    }

    /**
     * Add Custom Visitor Var
     * @return Varien_Object
     */
    public function addPageVar($name, $value) {
        return $this->addCustomVar($name, $value, self::SCOPE_PAGE);
    }

    /**
     * Random ID
     * @return int
     */
    public function getRandomId(){
        return rand(10000000,99999999);
    }

    /**
     * Request
     * @param Array $params
     * @return String
     */
    public function request($params){
        $query = http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::GA_URL . self::GA_BEACON . $query,
            CURLOPT_USERAGENT => $this->getData('user_agent')
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    /**
     * Get Cookie Params
     * @return String
     */
    public function getCookieParams($utma1 = null, $utma2 = null, $today = null) {
        $utma1  = !is_null($utma1) ? $utma1 : $this->getRandomId();
        $utma2  = !is_null($utma2) ? $utma2 : rand(0, 1147483647) + 1000000000;
        $today   = !is_null($today) ? $today : time();

        if (!$this->getData('utma')) {
            $this->setData('utma', "1." . $utma1 . "00145214523." . $utma2 . "." . $today . "." . $today . ".15");
        }
        if (!$this->getData('utmz')) {
            $this->setData('utmz', "1." . $today . ".1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)");
        }

        return "__utma=" . $this->getData('utma') . ";+__utmz=" . $this->getData('utmz')  . ";";
    }

    public function __toString(){
        $socket = fsockopen(GA_URL);
        return self::GA_URL . http_build_query($this->getData());
    }
}