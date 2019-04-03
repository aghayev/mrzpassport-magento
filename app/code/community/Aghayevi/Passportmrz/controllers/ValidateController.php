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
class Aghayevi_Passportmrz_ValidateController extends Mage_Core_Controller_Front_Action
{

    /**
     * Index Action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');

        $this->renderLayout();
    }

    /**
     * Post Action
     *
     * @return Mage_Core_Controller_Varien_Action
     */
    public function postAction()
    {

        $post = $this->getRequest()->getPost();
        if ($post) {
            $customer = $this->_getSession()->getCustomer();
            $passportMrzlp = $this->getRequest()->getPost('passport_mrzl1');
            $passportMrzlt = $this->getRequest()->getPost('passport_mrzl2');

            try {
                if ($passportMrzlp == "" && $passportMrzlt == "") {
                    return $this->_redirect('*/*/index');
                } else if (($passportMrzlp != "" && $passportMrzlt == "")
                    || ($passportMrzlp == "" && $passportMrzlt != "")
                ) {
                    Mage::throwException($this->__('Both Passport Mrz Code Lines must be filled.'));
                }

                Mage::helper('passportmrz')->validateLine1($passportMrzlp);
                Mage::helper('passportmrz')->validateLine2($passportMrzlt);

                $customer->setPassportMrzl1($passportMrzlp);
                $customer->setPassportMrzl2($passportMrzlt);
                $customer->save();
                $this->_getSession()->setCustomer($customer)
                    ->addSuccess($this->__('Your request has been submitted and is in progress, check back later.'));

                $this->_getSession()
                    ->setData('passport_mrzl1', null)
                    ->setData('passport_mrzl2', null);
            } catch (Mage_Core_Exception $e) {
                $this->_setMrzData()
                    ->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_setMrzData()
                    ->addException($e, $this->__('Unable to submit your request.'));
            }

            $this->_redirect('*/*/');
            return;
        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * Set Mrz Data
     *
     * @return Varien_Object
     */
    protected function _setMrzData()
    {
        return $this->_getSession()
            ->setData('passport_mrzl1', $this->getRequest()->getPost('passport_mrzl1'))
            ->setData('passport_mrzl2', $this->getRequest()->getPost('passport_mrzl2'));
    }

    /**
     * Get Session
     *
     * @return Mage_Core_Model_Abstract
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
}
