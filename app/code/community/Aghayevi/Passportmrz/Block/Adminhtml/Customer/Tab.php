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
class Aghayevi_Passportmrz_Block_Adminhtml_Customer_Tab
    extends Mage_Adminhtml_Block_Template
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Mrz data
     *
     * @var string $_passportMrzp
     * @var string $_passportMrzt
     */
    protected $_passportMrzlp;
    protected $_passportMrzlt;

    /**
     * Mrz Helper
     *
     * @var Aghayevi_Passportmrz_Helper_Mrz
     */
    protected $_mrzHelper;

    /**
     * Constructor
     */
    public function _construct()
    {
        $this->_mrzHelper = Mage::helper('passportmrz/mrz');
        $this->_passportMrzlp = $this->_getCustomer()->getPassportMrzl1();
        $this->_passportMrzlt = $this->_getCustomer()->getPassportMrzl2();
        parent::_construct();
        $this->setTemplate('passportmrz/tab.phtml');
    }

    /**
     * Get Tab Label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Passport Details');
    }

    /**
     * Get Tab Title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view your custom tab content');
    }

    /**
     * Can Show Tab
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Is Hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Get Passport 1 Line Mrz Code
     *
     * @return string|null
     */
    public function getPassportMrzl1()
    {
        return $this->_passportMrzlp;
    }

    /**
     * Get Passport 2 Line Mrz Code
     *
     * @return string|null
     */
    public function getPassportMrzl2()
    {
        return $this->_passportMrzlt;
    }

    /**
     * Get Passport Issuing Country
     *
     * @return string
     */
    public function getPassportIssuingCountry()
    {
        return $this->_mrzHelper->getPassportIssuingCountry($this->_passportMrzlp);
    }

    /**
     * Get Passport No
     *
     * @return string
     */
    public function getPassportNo()
    {
        return $this->_mrzHelper->getPassportNo($this->_passportMrzlt);
    }

    /**
     * Get Nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->_mrzHelper->getNationality($this->_passportMrzlt);
    }

    /**
     * Get Data Of Birth
     *
     * @return string
     */
    public function getDateOfBirth()
    {
        return $this->_mrzHelper->getDateOfBirth($this->_passportMrzlt);
    }

    /**
     * Get Sex
     *
     * @return string
     */
    public function getSex()
    {
        return $this->_mrzHelper->getSex($this->_passportMrzlt);
    }

    /**
     * Get Date Of Expiry
     *
     * @return string
     */
    public function getDateOfExpiry()
    {
        return $this->_mrzHelper->getDateOfExpiry($this->_passportMrzlt);
    }

    /**
     * Get Customer
     *
     * @return mixed
     */
    protected function _getCustomer()
    {
        return Mage::registry('current_customer');
    }
}