<?php

namespace Signature;

class Signature
{
    private $appid;
    private $appSecret;
    private $requestParams;

    private $signData;

    /**
     * API签名验证
     *
     * @param string $appid 提供给API调用者的身份标识
     * @param string $appSecret 提供给API调用者的密钥
     * @param array $requestParams 请求的其他参数，其中Key请不要包含空格
     * @return void
     */
    public function __construct($appid, $appSecret, $requestParams)
    {
        $this->appid = $appid;
        $this->appSecret = $appSecret;
        $this->requestParams = $requestParams;
        $signData = $requestParams;
        $signData['appid'] = $appid;
        $signData['appSecret'] = $appSecret;
        $this->signData = $signData;
    }

    /**
     * 生成签名密钥
     *
     * @return string
     */
    public function generate($timestamp = null, $nonce = null)
    {
        if (!$timestamp) {
            $timestamp = $this->getTimestamp();
        }
        if (!$nonce) {
            $nonce = $this->getNonceStr();
        }
        $this->signData['timestamp'] = $timestamp;
        $this->signData['nonce'] = $nonce;
        $queryStr = $this->getQueryStr();
        $signature = sha1($queryStr);
        return [
            'signature' => $signature,
            'nonce' => $nonce,
            'timestamp' => $timestamp,
        ];
    }

    private function getQueryStr()
    {
        $signData = $this->signData;
        $queryStrs = array_map(
            function ($key) use ($signData) {
                return strtolower($key) . '=' . $signData[$key];
            },
            array_keys($signData)
        );
        sort($queryStrs);
        return implode('&', $queryStrs);
    }

    /**
     * 生产随机数字字符串
     *
     * @return string
     */
    private function getNonceStr()
    {
        return (string) random_int(100000, 999999);
    }

    private function getTimestamp()
    {
        return time();
    }
}
