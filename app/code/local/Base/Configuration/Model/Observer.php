<?php

class Base_Configuration_Model_Observer
{
    public function test()
    {
        /* @var $cookie Mage_Core_Model_Cookie */
        $cookie = Mage::getModel('core/cookie');
        $cookie->set('test2','test');
        Mage::log(__FILE__,null,'base.log');
    }
}