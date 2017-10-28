<?php
define('MAGENTO_ROOT', getcwd());
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

umask(0);

Mage::app();

$helper = Mage::helper('payment');
foreach($helper->getStoreMethods() as $method) {
    echo get_class($method) ."\n";
}




