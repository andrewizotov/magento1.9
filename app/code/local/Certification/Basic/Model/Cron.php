<?php

class Certification_Basic_Model_Cron {
    public function send()
    {
        Mage::log( __CLASS__.'::'.__FUNCTION__. '/ '.date('m-d-Y H:i:s'),null,'base.log');
    }

    public function sendAlways()
    {
        Mage::log( __CLASS__.'::'.__FUNCTION__. '/ '.date('m-d-Y H:i:s'),null,'base.log');
    }

    public function sendADef()
    {
        Mage::log( __CLASS__.'::'.__FUNCTION__. '/ '.date('m-d-Y H:i:s'),null,'base.log');
    }
}