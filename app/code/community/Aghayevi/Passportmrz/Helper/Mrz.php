<?php

/**
 * PassportMrzValidation - Magento
 *
 * @category  Aghayevi
 * @package   Aghayevi_Passportmrz
 * @author    Imran Aghayev <imran.aghayev@hotmail.co.uk>
 * @copyright Imran Aghayev (http://www.aghayev.com)
 * @license   https://www.apache.org/licenses/LICENSE-2.0  Apache License version 2.0
 * @see       https://www.icao.int/publications/Documents/9303_p3_cons_en.pdf Document of Mrz standard
 */
class Aghayevi_Passportmrz_Helper_Mrz extends Mage_Core_Helper_Abstract
{

    /**
     * Filler character used in mrz zone
     */
    const FILLER_CHAR = '<';

    /**
     * Passport indicator character used in mrz zone
     */
    const PASSPORT_INDICATOR_CHAR = 'P';

    /**
     * Get Filler Char
     *
     * @return string
     */
    public function getFillerChar()
    {
        return self::FILLER_CHAR;
    }

    /**
     * Get Indication Character is Passport for Line 1
     *
     * @param string $data
     * @return bool
     */
    public function isPassport($data)
    {
        return strtoupper(substr($data, 0, 1)) == self::PASSPORT_INDICATOR_CHAR;
    }

    /**
     * Get Passport Issuing Country for Line 1
     *
     * @param $data
     * @return string|null
     */
    public function getPassportIssuingCountry($data)
    {
        return substr($data, 2, 3);
    }

    /**
     * Check Passport Issuing Country for Line 1
     *
     * @param string $data
     * @return bool
     */
    public function checkPassportIssuingCountry($data)
    {
        return $this->_checkCountryCode($this->getPassportIssuingCountry($data));
    }

    /**
     * Get Passport No
     *
     * @param string $data
     * @return string|null
     */
    public function getPassportNo($data)
    {
        return substr($data, 0, 9);
    }

    /**
     * Check Passport No
     *
     * @param string $data
     * @return bool
     */
    public function checkPassportNo($data)
    {
        $passportNo = $this->getPassportNo($data);
        return (int)substr($data, 9, 1) == $this->_calcCheckDigit($passportNo);
    }

    /**
     * Get Nationality
     *
     * @param string $data
     * @return string|null
     */
    public function getNationality($data)
    {
        return substr($data, 10, 3);
    }

    /**
     * Check Nationality
     *
     * @param string $data
     * @return bool
     */
    public function checkNationality($data)
    {
        return $this->_checkCountryCode($this->getNationality($data));
    }

    /**
     * Get Date of Birth
     *
     * @param string $data
     * @return string|null
     */
    public function getDateOfBirth($data)
    {
        return substr($data, 13, 6);
    }

    /**
     * Check Date Of Birth
     *
     * @param string $data
     * @return bool
     */
    public function checkDateOfBirth($data)
    {
        $dob = $this->getDateOfBirth($data);
        return (int)substr($data, 19, 1) == $this->_calcCheckDigit($dob);
    }

    /**
     * Get Sex
     *
     * @param string $data
     * @return string
     */
    public function getSex($data)
    {
        return substr($data, 20, 1);
    }

    /**
     * Get Date of Expiry
     *
     * @param string $data
     * @return string
     */
    public function getDateOfExpiry($data)
    {
        return substr($data, 21, 6);
    }

    /**
     * Check Date Of Expiry
     *
     * @param string $data
     * @return bool
     */
    public function checkDateOfExpiry($data)
    {
        $dateOfExpiry = $this->getDateOfExpiry($data);
        return (int)substr($data, 27, 1) == $this->_calcCheckDigit($dateOfExpiry);
    }

    /**
     * Get Optional Data
     *
     * @param string $data
     * @return string
     */
    public function getOptionalData($data)
    {
        return substr($data, 28, 14);
    }

    /**
     * Check Date Of Expiry
     * Based on icao 9303_p3_cons_en.pdf - APPENDIX A TO PART 3 EXAMPLES OF CHECK DIGIT CALCULATION (INFORMATIVE)
     *
     * @param string $data
     * @param string $optionalData
     * @return bool
     */
    public function checkOptionalData($data, $optionalData)
    {
        return (int)substr($data, 42, 1) == $this->_calcCheckDigit($optionalData);
    }

    /**
     * Get Optional Data Filler Chars
     *
     * @param string $data
     * @return string
     */
    public function getOptionalDataFillerChars()
    {
        return str_repeat(self::FILLER_CHAR, 14);
    }

    /**
     * Get Whole Line
     *
     * @param string $data
     * @return string
     */
    public function getWholeLine($data)
    {
        return substr($data, 0, 10) . substr($data, 13, 7) . substr($data, 21, 22);
    }

    /**
     * Check Whole Line 2
     *
     * @param string $data
     * @return bool
     */
    public function checkWholeLine($data)
    {
        $wholeLine = $this->getWholeLine($data);
        return (int)substr($data, 43, 1) == $this->_calcCheckDigit($wholeLine);
    }

    /**
     * Calculate Check Digit
     *
     * @param string $inputCode
     * @return int
     */
    protected function _calcCheckDigit($inputCode)
    {
        $btArray = str_split($inputCode);
        $total = 0;
        $count = count($btArray);

        for ($index = 0; $index < $count; $index++) {
            $btChr = ord($btArray[$index]);

            if ($btChr == 60) {
                $btArray[$index] = 0;
            } else if ($btChr >= 65) {
                $btArray[$index] = $btChr - 55;
            } else {
                $btArray[$index] = $btChr - 48;
            }

            switch ($index % 3) {
                case 0:
                    $btArray[$index] *= 7;
                    break;
                case 1:
                    $btArray[$index] *= 3;
                    break;
                case 2:
                    $btArray[$index] *= 1;
                    break;
            }

            $total += $btArray[$index];
        }

        return (int)$total % 10;
    }

    /**
     * Check Country Code
     *
     * @param string $input
     * @return bool
     */
    protected function _checkCountryCode($input)
    {

        $input = str_replace(self::FILLER_CHAR, '', $input);

        $extra = array(
            'D', 'EUE', 'GBD', 'GBN', 'GBP', 'GBS', 'UNA', 'UNK', 'UNO', 'XBA',
            'XIM', 'XCC', 'XCO', 'XPO', 'XOM', 'XXA', 'XXB', 'XXC', 'XXX', 'RKS', 'WSA'
        );
        $countries = array();

        $countriesCollection = Mage::getModel('directory/country')->getCollection();
        foreach ($countriesCollection as $country) {
            if ($country->getIso3Code() != 'DEU') {
                $countries[] = $country->getIso3Code();
            }
        }

        $countriesSearch = array_flip(array_merge($countries, $extra));
        return isset($countriesSearch[$input]);
    }
}
