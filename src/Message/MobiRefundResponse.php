<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class MobiResponse
 * @package Omnipay\Mpay\Message
 */
class MobiRefundResponse extends AbstractResponse
{
    public function isRedirect()
    {
        return false;
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectUrl()
    {
        return false;
    }


    public function getRedirectHtml()
    {
        return false;
    }


    public function getTransactionNo()
    {
        return isset($this->data['refnum']) ? $this->data['refnum'] : '';
    }


    public function isPaid()
    {
        if ($this->data['is_paid']) {
            return true;
        }

        return false;
    }


    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data['is_paid']) {
            return true;
        }

        return false;
    }


    public function getMessage()
    {
        return isset($this->data['remark']) ? $this->data['remark'] : '';
    }
}
