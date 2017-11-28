<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiRefundRequest
 *
 * @package Omnipay\Mpay\Message
 */
class MobiRefundRequest extends AbstractMobiRequest
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
            //商户支付订单号
            'org_ordernum' => $this->getOrdernum(),
            //商户退款订单号
            'ordernum'     => $this->getRefundOrdernum(),
            //Mpay支付订单号
            'org_ref'      => $this->getRef(),
            //支付类型
            'cardtype'     => $this->getCardtype(),
            //退款金额
            'amount'       => $this->getRefundAmt(),
            //哈希码
            'securekey'    => $this->getSecurekey(),
            //mpay查询网关
            'gateway'      => $this->getEndpoint('refund')
        );

        return $data;
    }

    private function validateData()
    {
        $this->validate(
            'merchantid',
            'merchant_tid',
            'ordernum',
            'refund_ordernum',
            'ref',
            'cardtype',
            'refund_amt',
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

        $requestMessage = $data['merchantid'] . $delr .
            $data['merchant_tid'] . $delr .
            $data['ordernum'] . $delr .
            $data['org_ordernum'] . $delr .
            $data['org_ref'] . $delr .
            $data['cardtype'] . $delr .
            $data['amount'];

        $hash = Helper::genHashRequest($requestMessage, $salt, $data['securekey']);

        // soap请求
        try {
            $client = new \SoapClient($data['gateway'], array('encoding' => 'UTF-8'));
            $client->__setLocation($data['gateway']);

            $param = array(
                'arg0' => $data['merchantid'],
                'arg1' => $data['merchant_tid'],
                'arg2' => $data['ordernum'],
                'arg3' => $data['org_ordernum'],
                'arg4' => $data['org_ref'],
                'arg5' => $data['cardtype'],
                'arg6' => $data['amount'],
                'arg7' => $salt,
                'arg8' => $hash
            );

            // 获取返回数据
            $result = $client->__soapCall("performTxnRefund", array($param));
        } catch (\SOAPFault $e) {
            print $e;
        }

        $return = (array)$result->return;

        $merchantid   = $result->return->merchantid;
        $merchant_tid = $result->return->merchant_tid;
        $ordernum     = $result->return->ordernum;
        $refnum       = $result->return->refnum;
        // 将amount从float转化为string格式
        $amount       = (string) $result->return->amt;
        $rspcode      = $result->return->rspcode;
        $salt         = $result->return->salt;
        $hash         = $result->return->hash;
        $cardtype     = $result->return->cardtype;

        // 将amount转化为100.0格式
        if(strpos($amount, '.') === false){
            $amount = (string)$amount;
            $amount = $amount . ".0";
        }

        $responseMessage = $merchantid . $delr . $merchant_tid . $delr . $ordernum . $delr . $refnum . $delr . $amount . $delr . $rspcode;
        $hashvalue       = Helper::genHashResponse($responseMessage, $salt, $data['securekey']);

        $data['is_paid'] = false;
        if (strcasecmp($hash, $hashvalue) == 0
            && $rspcode === "100"
            && $cardtype == $this->getCardtype()
        ) {
            $data['is_paid'] = true;
        }

        $data = array_merge($data, $return);

        return $this->response = new MobiRefundResponse($this, $data);
    }
}