# 使用说明

## 客户端生成签名

```php
function test()
{
    $appid = '27834yrudfhao7rfouah';
    $appSecret = '0239urq98eurqwfff743q2yf8o7whaeofy3q74rhfoq7fghw';

    $params = [
        'id' => '123',
        'goods_id' => 'abcd123',
        'comment' => '测试Signature',
    ];

    $s = new Signature($appid, $appSecret, $params);
    var_dump($s->generate());
}

test();
```

请求中必须包含`appid`,`nonce`,`timestamp`,`signature`和参与签名的数据，不能多字段也不能少字段。

## 服务端验证签名

```php
$queryData = $request->all();
$appid = array_get($queryData, 'appid');
if (empty($appid)) {
    throw new \Exception("签名校验失败：appid must not empty");
}
$appAuth = ApiAuthModel::where(['appid' => $appid])->first();
if (!$appAuth) {
    throw new \Exception("签名校验失败：appid invalid");
}
$appSecret = $appAuth->app_secret;
$v = new SignatureValidate($queryData, $appSecret);
if (!$v->validate()) {
    throw new \Exception("签名校验失败：signature invalid");
}
```
