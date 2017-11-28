<?php
namespace Omnipay\Mpay;

/**
 * Class Helper
 * @package Omnipay\Mpay
 */
class Helper
{
    public static function genSalt()
    {
        $salt = self::genRandomString(16);
        return $salt;
    }

    public static function genHashValue($merchantid, $merchant_tid, $ordernum, $datetime, $amt, $currency, $cardtype, $locale, $returnurl, $notifyurl, $customizeddata, $extrafield1, $extrafield2, $extrafield3, $salt, $securekey)
    {
        $delr = ";";
        $plainText = $ordernum . $delr . $datetime . $delr . $merchant_tid . $delr . $merchantid . $delr . substr($salt, 1, 8) . $delr . $securekey . $delr . $amt . $delr . $currency . $delr . $cardtype;
        $hash = hash("sha256", $plainText);
        return $hash;
    }

    public static function genHashValueResponse($merchantid, $ordernum, $sysdatetime, $ref, $amt, $currency, $settledate, $rspcode, $customizeddata, $return_payno, $fi_post_dt, $salt, $securekey)
    {
        $delr = ";";
        $plainText = $amt . $delr . $ref . $delr . $ordernum . $delr . substr($salt, 1, 8) . $delr . $securekey . $delr . $merchantid . $delr . $currency . $delr . $rspcode . $delr . $return_payno;
        $hash = hash("sha256", $plainText);
        return $hash;
    }

    private static function genRandomString($length)
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $charactersLength = strlen($characters);
        $randomString = "";
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function genHashRequest($requestMessage, $salt, $securekey)
    {
        $delr = ";";
        $plainText = $requestMessage . $delr . substr($salt, 1, 8) . $delr . $securekey;
        $hash = hash("sha256", $plainText);
        return $hash;
    }

    public static function genHashResponse($responseMessage, $salt, $securekey)
    {
        $delr = ";";
        $plainText = $responseMessage . $delr . substr($salt, 1, 8) . $delr . $securekey;
        $hash = hash("sha256", $plainText);
        return $hash;
    }

    public static function sendHttpRequest($url, $params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-type:application/x-www-form-urlencoded;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public static function arrayToXml($data)
    {
        $xml = "<?xml version='1.0' encoding='UTF-8'?>";

        $xml .= "<soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>";

        $xml .= "<soap:Header>";
        $xml .= "</soap:Header>";

        $xml .= "<soap:Body>";

        $xml .= "<soap:Fault>";

        foreach ($data as $key=>$val)
        {
            $xml .= "<" . $key . ">" . htmlspecialchars($val) . "</" . $key . ">";
        }

        $xml .= "</soap:Fault>";

        $xml .= "</soap:Body>";

        $xml .= "</soap:Envelope>";

//        return $xml;

        return '<?xml version="1.0" encoding="UTF-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>

<performTxnEnquiry:Sequence  xmlns:performTxnEnquiry="http://ws.mpay.com/">
<merchantid>564</merchantid>
<merchant_tid>45</merchant_tid>
<ordernum>9999</ordernum>
<salt>55</salt>
<hash>44</hash>
</performTxnEnquiry:Sequence>

</soap:Body>
</soap:Envelope>
';
    }
}
