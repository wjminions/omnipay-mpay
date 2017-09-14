<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiCompletePurchaseRequest
 * @package Omnipay\Mpay\Message
 */
class MobiCompletePurchaseRequest extends AbstractMobiRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->getRequestParams();
    }


    public function setRequestParams($value)
    {
        $this->setParameter('request_params', $value);
    }


    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }


    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        $merchantid = $this->getMerchantid();

        $ordernum = $data["ordernum"];
        $ref = $data["ref"];
        $amt = $data["amt"];
        $currency = $data["currency"];
        $rspcode = $data["rspcode"];
        $customizeddata = $data["customizeddata"];
        $authcode = $data["authcode"];
        $fi_post_dt = $data["fi_post_dt"];
        $sysdatetime = $data["sysdatetime"];
        $settledate = $data["settledate"];
        $cardtype = $data["cardtype"];
        $salt = $data["salt"];
        $hash = $data["hash"];
        $securekey = $this->getSecurekey();

        $hashvalue = Helper::genHashValueResponse($merchantid, $ordernum, $sysdatetime, $ref,
            $amt, $currency, $settledate, $rspcode, $customizeddata, $authcode, $fi_post_dt,
            $salt, $securekey);

        $data['is_paid'] = false;
        if (strcasecmp($hash, $hashvalue) == 0
            && $rspcode === "100"
            && $cardtype == $this->getCardtype()
        ) {
            $data['is_paid'] = true;
        }

        return $this->response = new MobiCompletePurchaseResponse($this, $data);
    }
}
