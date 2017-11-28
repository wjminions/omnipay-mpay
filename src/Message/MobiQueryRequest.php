<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiQueryRequest
 *
 * @package Omnipay\Mpay\Message
 */
class MobiQueryRequest extends AbstractMobiRequest
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
            'merchantid'   => $this->getMerchantid(),
            //商户终端ID
            'merchant_tid' => $this->getMerchantTid(),
            //商户订单号
            'ordernum'     => $this->getOrdernum(),
            //哈希码
            'securekey'    => $this->getSecurekey(),
            //mpay查询网关
            'gateway'      => $this->getEndpoint('query')
        );

        return $data;
    }


    private function validateData()
    {
        $this->validate(
            'merchantid',
            'merchant_tid',
            'ordernum',
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
        $salt = Helper::genSalt();

        $delr = "|";

        $requestMessage = $data['merchantid'] . $delr . $data['merchant_tid'] . $delr . $data['ordernum'];

        $hash = Helper::genHashRequest($requestMessage, $salt, $data['securekey']);

        // soap请求
        try {
            $client = new \SoapClient($data['gateway'], array('encoding' => 'UTF-8'));
            $client->__setLocation($data['gateway']);

            $param = array(
                'arg0' => $data['merchantid'],
                'arg1' => $data['merchant_tid'],
                'arg2' => $data['ordernum'],
                'arg3' => $salt,
                'arg4' => $hash
            );

            // 获取返回数据
            $result = $client->__soapCall("performTxnEnquiry", array($param));
        } catch (\SOAPFault $e) {
            print $e;
        }

        $return = (array) $result->return;

        $merchantid   = $result->return->merchantid;
        $merchant_tid = $result->return->merchant_tid;
        $ordernum     = $result->return->ordernum;
        $refnum       = $result->return->refnum;
        $rspcode      = $result->return->rspcode;
        $salt         = $result->return->salt;
        $hash         = $result->return->hash;
        $cardtype     = $result->return->cardtype;

        $responseMessage = $merchantid . $delr . $merchant_tid . $delr . $ordernum . $delr . $refnum . $delr . $rspcode;
        $hashvalue    = Helper::genHashResponse($responseMessage, $salt, $data['securekey']);

        $data['is_paid'] = false;
        if (strcasecmp($hash, $hashvalue) == 0
            && $rspcode === "100"
            && $cardtype == $this->getCardtype()
        ) {
            $data['is_paid'] = true;
        }

        $data = array_merge($data, $return);

        return $data;
    }
}
