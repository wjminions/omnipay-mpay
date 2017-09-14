<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Class MobiCompletePurchaseResponse
 * @package Omnipay\Mpay\Message
 */
class MobiCompletePurchaseResponse extends AbstractResponse
{

    public function isPaid()
    {
        return $this->data['is_paid'];
    }


    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data['is_paid'];
    }


    public function getTransactionId()
    {
        return $this->data['ref'];
    }
}
