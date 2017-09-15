<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiPurchaseResponse
 * @package Omnipay\Mpay\Message
 */
class MobiPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful()
    {
        return true;
    }


    public function isRedirect()
    {
        return true;
    }


    public function getRedirectUrl()
    {
        return false;
    }


    public function getRedirectMethod()
    {
        return 'POST';
    }


    public function getRedirectData()
    {
        return false;
    }


    public function getMessage()
    {
        return $this->data;
    }


    public function getRedirectHtml()
    {

        $data = $this->data;
        
        $merchantid = $data["merchantid"];
        $merchant_tid = $data["merchant_tid"];
        $ordernum = $data["ordernum"];
        $datetime = $data["datetime"];
        $amt = $data["amt"];
        $currency = $data["currency"];
        $cardtype = $data["cardtype"];
        $returnurl = $data["returnurl"];
        $customizeddata = $data["customizeddata"];
        $locale = $data["locale"];
        $notifyurl = $data["notifyurl"];

        $extrafield1 = "";
        $extrafield2 = "";
        $extrafield3 = "";
        $securekey = $data['securekey'];

        $gateway = $data['gateway'];

        $salt = Helper::genSalt();
        $hash = Helper::genHashValue($merchantid, $merchant_tid, $ordernum, $datetime,
            $amt, $currency, $cardtype, $locale,
            $returnurl, $notifyurl, $customizeddata,
            $extrafield1, $extrafield2, $extrafield3,
            $salt, $securekey);

        $html = <<<eot
<html>
<head></head>
<body onload="javascript:document.pay_form.submit();">
<form id="pay_form" name="pay_form" method="post" action="{$gateway}">
	<input type="hidden" name="merchantid" value="{$merchantid}" />
	<input type="hidden" name="merchant_tid" value="{$merchant_tid}" />
	<input type="hidden" name="ordernum" value="{$ordernum}" />
	<input type="hidden" name="datetime" value="{$datetime}" />
	<input type="hidden" name="amt" value="{$amt}" />
	<input type="hidden" name="currency" value="{$currency}" />
	<input type="hidden" name="cardtype" value="{$cardtype}" />
	<input type="hidden" name="locale" value="{$locale}" />
	<input type="hidden" name="returnurl" value="{$returnurl}" />
	<input type="hidden" name="notifyurl" value="{$notifyurl}" />
	<input type="hidden" name="customizeddata" value="{$customizeddata}" />
	<input type="hidden" name="extrafield1" value="{$extrafield1}" />
	<input type="hidden" name="extrafield2" value="{$extrafield2}" />
	<input type="hidden" name="extrafield3" value="{$extrafield3}" />
	<input type="hidden" name="salt" value="{$salt}" />
	<input type="hidden" name="hash" value="{$hash}" />
</form>
</body>
</html>
eot;

        return $html;
    }
}
