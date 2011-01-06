<?php
/* @var $install Mage_Eav_Model_Entity_Setup */
$install = $this;
$install->startSetup();

$install->addAttribute('catalog_category','some_attr',array(
    'backend_type' => 'text',
    'input'  => 'select',
    'source' => 'Base_Configuration_Model_Source_SomeAttr'

));

$install->endSetup();