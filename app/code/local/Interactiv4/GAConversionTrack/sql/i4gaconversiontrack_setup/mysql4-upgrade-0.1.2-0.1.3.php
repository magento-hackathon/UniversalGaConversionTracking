<?php
/**
 * GAConversionTrack
 *
 * @category    Interactiv4
 * @package     Interactiv4_GAConversionTrack
 * @copyright   Copyright (c) 2012 Interactiv4 SL. (http://www.interactiv4.com)
 */
$this->startSetup();

$this->addAttribute('order', 'i4gaconversiontrack_user_agent', array('type' => 'text'));
$this->addAttribute('order', 'i4gaconversiontrack_screen_resolution', array('type' => 'text'));
$this->addAttribute('order', 'i4gaconversiontrack_screen_color_depth', array('type' => 'text'));
$this->addAttribute('order', 'i4gaconversiontrack_browser_language', array('type' => 'text'));
$this->addAttribute('order', 'i4gaconversiontrack_browser_java_enabled', array('type' => 'int'));

$this->endSetup();
