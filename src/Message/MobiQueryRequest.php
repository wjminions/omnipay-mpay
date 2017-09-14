<?php

namespace Omnipay\Mpay\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mpay\Helper;

/**
 * Class MobiQueryRequest
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
        $this->validate(
            'app_key',
            'ch_id'
        );

        $data = array (
            'body' => $this->getBody(),
            //app_id
            'app' => $this->getApp(),
            //支付方式
            'channel' => $this->getChannel(),
            //callback地址
            'callback' => $this->getCallback(),
            //app_key
            'app_key' => $this->getAppKey(),
            //货币
            'currency' => $this->getCurrency(),
            //私钥地址
            'private_key_path' => $this->getPrivateKeyPath(),
            //交易id
            'ch_id' => $this->getChId()
        );

        return $data;
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

        \Mpay\Mpay::setApiKey($data['app_key']);           // 设置 API Key
        \Mpay\Mpay::setPrivateKeyPath($data['private_key_path']);   // 设置私钥

        // 通过发起一次退款请求创建一个新的 refund 对象，只能对已经发生交易并且没有全额退款的 Mobi 对象发起退款
        $ch = \Mpay\Mobi::retrieve($data['ch_id']);// Mobi 对象的 id

        return json_decode($ch);// 输出 Ping++ 返回 Mobi 对象
    }
}