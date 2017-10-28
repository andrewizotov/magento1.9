<?php

class Certification_Basic_Block_Paymentnew extends Mage_Payment_Block_Form {
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('paymentnew/form/form.phtml');
    }
}