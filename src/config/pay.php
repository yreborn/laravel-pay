<?php

return   [
    //应用ID,您的APPID。
    'appId' => "",

    //商户私钥
    'merchantPrivateKey' => "",

    //异步通知地址
    'notifyUrl' => "",

    //同步跳转
    'return_url' => "",

    //签名方式
    'signType'=>"RSA2",

    //支付宝网关
    'gatewayHost' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipayPublicKey' => "",


    'merchantId' => '',
    'merchantCertificateSerial' => '',
    'merchantPrivateKeyFilePath' => '',
    'platformCertificateFilePath' => '',
    'wxAppId' => '',
    'wxNotifyUrl' => '',
];