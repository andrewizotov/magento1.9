<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();
$conn = $installer->getConnection();
$table = new Varien_Db_Ddl_Table();
$table->setName('tttt');
$table->addColumn('gg','integer');
$conn->createTable($table);
$installer->endSetup();