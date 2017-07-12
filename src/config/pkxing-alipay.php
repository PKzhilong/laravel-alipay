<?php
return [
    //应用ID,您的APPID。
    'app_id' => "2017062807585428",

    //商户私钥
    'merchant_private_key' => storage_path() . '/cert/alipay/rsa_private_key.pem', //这里填写商户私钥 的绝对地址 .pem文件， 当时生成的private_key.pem文件

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝商户唯一id（合作伙伴身份id）
    'seller_id' => '2088621866919631',
    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAo7jpUXLpLN1/tEtqzEgVfNwz4BKo+ei53GWB/SdcdJGUdpjWxSyjiZIeVnCLWtHuhxWdlPggRkaYjl1eSreFUraNcCdiQNuxM6vu7kbjHCWAStkq0cFswPqBOD96eYAf1fQpVXJdvjF62JhGWld05eYmqA5ZngyYd7zsGWSRoRjwP+HH2SYRlEvobh2R4uCdPa33ipDTWKdZWw6/lPbWYq/kedaBCnIMk4QKpV2Twx4n2zcwkno+Zg6YCo7MflWzlThJzphQa4TidU3zh8yBcuII6LHKML7bh/HR4GC5pQ3btsgITpYIo9QPZNDmZ1XlxjYh7eOrKtQol3Rcvt8OFwIDAQAB',
];