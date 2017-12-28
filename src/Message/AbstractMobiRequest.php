<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Mpay\Helper;

/**
 * Class AbstractMobiRequest
 * @package Omnipay\Mpay\Message
 */
abstract class AbstractMobiRequest extends AbstractRequest
{
    protected $sandboxEndpoint = 'https://demo.mobiletech.com.hk/';

    protected $productionEndpoint = 'https://www.mpaymall.com/';

    protected $methods = array (
        'pay' => '/MerchantPay.jsp',
        'query' => 'MPayJWS/TxnEnquiry?wsdl',
        'refund' => 'MPayJWS/TxnRefund?wsdl',
    );

    public function getEndpoint($type)
    {
        if ($this->getEnvironment() == 'production') {
            if ($type != 'pay') {
                return $this->productionEndpoint . $this->methods[$type];
            }

            return $this->productionEndpoint . $this->getChannel() . $this->methods[$type];
        } else {
            if ($type != 'pay') {
                return $this->sandboxEndpoint . $this->methods[$type];
            }

            return $this->sandboxEndpoint . $this->getChannel() . $this->methods[$type];
        }
    }


    public function setMerchantid($value)
    {
        return $this->setParameter('merchantid', $value);
    }


    public function getMerchantid()
    {
        return $this->getParameter('merchantid');
    }


    public function setMerchantTid($value)
    {
        return $this->setParameter('merchant_tid', $value);
    }


    public function getMerchantTid()
    {
        return $this->getParameter('merchant_tid');
    }


    public function setOrdernum($value)
    {
        return $this->setParameter('ordernum', $value);
    }


    public function getOrdernum()
    {
        return $this->getParameter('ordernum');
    }


    public function setDatetime($value)
    {
        return $this->setParameter('datetime', $value);
    }


    public function getDatetime()
    {
        return $this->getParameter('datetime');
    }


    public function setCardtype($value)
    {
        return $this->setParameter('cardtype', $value);
    }


    public function getCardtype()
    {
        return $this->getParameter('cardtype');
    }


    public function setReturnurl($value)
    {
        return $this->setParameter('returnurl', $value);
    }


    public function getReturnurl()
    {
        return $this->getParameter('returnurl');
    }


    public function setNotifyurl($value)
    {
        return $this->setParameter('notifyurl', $value);
    }


    public function getNotifyurl()
    {
        return $this->getParameter('notifyurl');
    }


    public function setCustomizeddata($value)
    {
        return $this->setParameter('customizeddata', $value);
    }


    public function getCustomizeddata()
    {
        return $this->getParameter('customizeddata');
    }


    public function setLocale($value)
    {
        return $this->setParameter('locale', $value);
    }


    public function getLocale()
    {
        return $this->getParameter('locale');
    }


    public function setSecurekey($value)
    {
        return $this->setParameter('securekey', $value);
    }


    public function getSecurekey()
    {
        return $this->getParameter('securekey');
    }


    public function setAmt($value)
    {
        return $this->setParameter('amt', $value);
    }


    public function getAmt()
    {
        return $this->getParameter('amt');
    }


    public function setChannel($value)
    {
        return $this->setParameter('channel', $value);
    }


    public function getChannel()
    {
        return $this->getParameter('channel');
    }


    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }


    public function getCurrency()
    {
        return $this->getParameter('currency');
    }


    public function setEnvironment($value)
    {
        return $this->setParameter('environment', $value);
    }


    public function getEnvironment()
    {
        return $this->getParameter('environment');
    }


    public function setRefundOrdernum($value)
    {
        return $this->setParameter('refund_ordernum', $value);
    }


    public function getRefundOrdernum()
    {
        return $this->getParameter('refund_ordernum');
    }


    public function setRefundAmt($value)
    {
        return $this->setParameter('refund_amt', $value);
    }


    public function getRefundAmt()
    {
        return $this->getParameter('refund_amt');
    }


    public function setRef($value)
    {
        return $this->setParameter('ref', $value);
    }


    public function getRef()
    {
        return $this->getParameter('ref');
    }
}
