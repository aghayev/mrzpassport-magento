<?php

$installer = $this;
$installer->startSetup();

$setup = Mage::getModel('customer/entity_setup', 'core_setup');

$setup->addAttribute(
    'customer', 'passport_mrzl1', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Passport number line 1',
    'global' => true,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'visible_on_front' => true
    )
);

$setup->addAttribute(
    'customer', 'passport_mrzl2', array(
    'type' => 'varchar',
    'input' => 'text',
    'label' => 'Passport number line 2',
    'global' => true,
    'visible' => true,
    'required' => false,
    'user_defined' => true,
    'visible_on_front' => true
    )
);

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'passport_mrzl1');
$attribute->save();

$attributen = Mage::getSingleton('eav/config')->getAttribute('customer', 'passport_mrzl2');
$attributen->save();

$this->endSetup();