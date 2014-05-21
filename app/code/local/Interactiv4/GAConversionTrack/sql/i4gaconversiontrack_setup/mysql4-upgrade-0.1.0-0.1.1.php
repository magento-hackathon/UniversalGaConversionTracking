<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */
$this->startSetup();

$this->run("UPDATE {$this->getTable('sales/order')} SET i4gaconversiontrack_tracked = 1;");

$this->endSetup();
