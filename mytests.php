<?php
define('MAGENTO_ROOT', getcwd());
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

umask(0);

Mage::app();


$baseTest  = Mage::getModel('Pelago_Emogrifier');
//$product  =  Mage::app()->getConfig()->getModelInstance('catalog/product');
var_dump(get_class($baseTest));

//echo get_class($baseTest)."\n";




