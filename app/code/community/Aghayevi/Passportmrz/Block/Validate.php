<?php

/**
 * PassportMrzValidation - Magento
 *
 * @category  Aghayevi
 * @package   Aghayevi_Passportmrz
 * @author    Imran Aghayev <imran.aghayev@hotmail.co.uk>
 * @copyright Imran Aghayev (http://www.aghayev.com)
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache License version 2.0
 */
class Aghayevi_Passportmrz_Block_Validate extends Mage_Customer_Block_Form_Edit
{

    /**
     * Get 1st passport mrz line data
     *
     * @return string|null
     */
    public function getPassportMrzl1()
    {
        return Mage::getSingleton('customer/session')->getData('passport_mrzl1');
    }

    /**
     * Get 2nd passport mrz line data
     *
     * @return string|null
     */
    public function getPassportMrzl2()
    {
        return Mage::getSingleton('customer/session')->getData('passport_mrzl2');
    }

    /**
     * Get Filler Char
     *
     * @return string
     */
    public function getFillerChar()
    {
        return Mage::helper('passportmrz/mrz')->getFillerChar();
    }
}
