<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiPurchaseRequest
 *
 * @package Omnipay\Mpay\Message
 */
class MobiPurchaseRequest extends AbstractMobiRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();

        $data = array(
            //商户ID
            'merchantid'     => $this->getMerchantid(),
            //商户终端ID
            'merchant_tid'   => $this->getMerchantTid(),
            //商户订单号
            'ordernum'       => $this->getOrdernum(),
            //支付时间
            'datetime'       => $this->getDatetime(),
            //支付金额
            'amt'            => $this->getAmt(),
            //支付的方式的代号
            'cardtype'       => $this->getCardtype(),
            ///callback地址
            'returnurl'      => $this->getReturnurl(),
            //货币
            'currency'       => $this->getCurrency(),
            //订单备注
            'customizeddata' => $this->getCustomizeddate(),
            //语言
            'locale'         => $this->getLocale(),
            //callback地址
            'notifyurl'      => $this->getNotifyurl(),
            //哈希码
            'securekey'      => $this->getSecurekey(),
            //mpay支付网关
            'gateway'        => $this->getEndpoint('pay')
        );

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'merchantid',
            'merchant_tid',
            'ordernum',
            'datetime',
            'amt',
            'cardtype',
            'returnurl',
            'currency',
            'customizeddata',
            'locale',
            'notifyurl',
            'securekey'
        );
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new MobiPurchaseResponse($this, (array)json_decode($data));
    }
}
