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
class Aghayevi_Passportmrz_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Validate 1st passport mrz line data
     *
     * @param string $data
     * @return bool
     */
    public function validateLine1($data)
    {
        $errors = array();
        $mrzHelper = $this->_getMrzHelper();

        if (!$mrzHelper->isPassport($data)) {
            $errors[] = $this->__('Passport indication');
        }

        if (!$mrzHelper->checkPassportIssuingCountry($data)) {
            $errors[] = $this->__('Issuing country code');
        }

        if ($errors) {
            $this->_log($data, $this->__('Line 1 error occured: ') . implode(', ', $errors), Zend_Log::ERR);
            $this->_validateError();
        }

        return true;
    }

    /**
     * Validate 2nd passport mrz line data
     *
     * @param string $data
     * @return bool
     */
    public function validateLine2($data)
    {
        $errors = array();
        $mrzHelper = $this->_getMrzHelper();

        if (!$mrzHelper->checkPassportNo($data)) {
            $errors[] = $this->__('Document number');
        }

        if (!$mrzHelper->checkNationality($data)) {
            $errors[] = $this->__('Nationality code');
        }

        if (!$mrzHelper->checkDateOfBirth($data)) {
            $errors[] = $this->__('Date of birth');
        }

        if (!$mrzHelper->checkDateOfExpiry($data)) {
            $errors[] = $this->__('Date of expiry');
        }

        $optionalData = $mrzHelper->getOptionalData($data);
        if ($optionalData != $mrzHelper->getOptionalDataFillerChars()) {
            if (!$mrzHelper->checkOptionalData($data, $optionalData)) {
                $errors[] = $this->__('Optional data');
            }
        }

        if (!$mrzHelper->checkWholeLine($data)) {
            $errors[] = $this->__('Full length check');
        }

        if ($errors) {
            $this->_log($data, $this->__('Line 2 error occured: ') . implode(', ', $errors), Zend_Log::ERR);
            $this->_validateError();
        }

        return true;
    }

    /**
     * Validate error
     */
    protected function _validateError()
    {
        Mage::throwException($this->__('The passport number you entered isn\'t valid. Please try again.'));
    }

    /**
     * Write to log
     *
     * @param string $code
     * @param string $message
     * @param int $level
     * @return void
     */
    protected function _log($code, $message, $level = null)
    {
        $errorMessage = sprintf("(Code: %s) - %s ", $code, $message);
        Mage::log($errorMessage, $level, 'passportmrz.log');
    }

    /**
     * @return Aghayevi_Passportmrz_Helper_Mrz
     */
    protected function _getMrzHelper()
    {
        return Mage::helper('passportmrz/mrz');
    }
}
