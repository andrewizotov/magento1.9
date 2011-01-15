<?php

class Certification_Basic_Model_Cron {
    public function send()
    {
        Mage::log(date('m-d-Y H:i:s'),null,'base.log');
    }
}