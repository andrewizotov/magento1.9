<?php
define('MAGENTO_ROOT', getcwd());
$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
require MAGENTO_ROOT . '/app/bootstrap.php';
require_once $mageFilename;

umask(0);

Mage::app();


//$baseTest  = Mage::getModel('Pelago_Emogrifier');
//$product  =  Mage::app()->getConfig()->getModelInstance('catalog/product');
//var_dump(get_class($baseTest));

 //$lastRun = Mage::app()->loadCache('cron_last_schedule_generate_at');
 //echo date('d-m-Y H:i:s', $lastRun)."\n";
//echo get_class($baseTest)."\n";

/* @var $coreConfigData Mage_Core_Model_Config_Data */
$coreConfigData = Mage::getModel('core/config_data');
$coreConfigData
    ->setPath('crontab/jobs/write_in_log_def/schedule/cron_expr')
    ->setValue('*/1 * * * *')
    ->setScope('default')
    ->save();






