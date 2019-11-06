<?php

namespace Signature;

class SignatureValidate
{
    private $appSecret;

    private $queryData;

    public function __construct($queryData, $appSecret)
    {
        $this->queryData = $queryData;
        $this->appSecret = $appSecret;
    }

    public function validate()
    {
        $required = ['appid', 'signature', 'nonce', 'timestamp'];
        foreach ($required as $k) {
            if (!array_key_exists($k, $this->queryData)) {
                throw new \Exception("请求数据必须包含appid/signature/nonce/timestamp字段");
            }
        }
        $appid = $this->queryData['appid'];
        $signature = $this->queryData['signature'];
        $nonce = $this->queryData['nonce'];
        $timestamp = $this->queryData['timestamp'];
        unset($this->queryData['signature']);
        $s = new Signature($appid, $this->appSecret, $this->queryData);
        $reSign = $s->generate($timestamp, $nonce);
        return $reSign['signature'] === $signature;
    }
}
