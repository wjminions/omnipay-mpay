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
}
