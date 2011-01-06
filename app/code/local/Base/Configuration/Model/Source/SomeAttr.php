<?php

class Base_Configuration_Model_Source_SomeAttr extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{
    function getAllOptions(){
        return [
            array(
                'label' => 'dog',
                'value' => 'dog'
            ),
            array(
                'label' => 'cat',
                'value' => 'cat'
            ),
        ];
    }
}