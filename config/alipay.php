<?php
return[
    //应用ID,您的APPID。
    'app_id' => "2016092500596176",
//    商户ID
    'seller_id' => "2088102177264820",
    //商户私钥
    'merchant_private_key' => "MIIEogIBAAKCAQEAwqL5Mdc4fcCCTiE9JghR0k3NOV/yxryTHDkGE24GkSCofgceckZiHiCOxOFYwRr2xsGT+00D3N4YAFqp5ZI85lBD+y3fpY2bZvvPVtG/DwMov7jRyvMlC1WyJKN2H8iti6n6R6qaPizPf+WtyB/tOOXBA2+lD8uJNkpOY028pEIJfN0HFZ9OAH6qH11jOpwcOiC5wbhZm3N0kLzodFksVA1d3GPpstHd2O6Y+nHwFyyYN7+GXhU10PYObfWG5ocHBZW+vMvmOdcg41qmc2jctadnYT2yKT/LEf3xmsf+BpaHos5vKI2S8n+hlZfmgwEj7Y/OXWS8Uazgl4yMJbMU1wIDAQABAoIBADWY6WRXLuA6Jefb8VnKyrVN5WiaSmF9xkjsTwcEslw+kQd7cF/pj9VaA9alc2phwXkED3sRPzIQGq3mSYjFV23Yxo+hl0egkh2u0+7FfYZlyU/aVjM794SCibbbKIkiGuAt45TenvDmKBHBLrTATfPwGNGYaezKkLNZjPJ9kqfsaFRZ7KPmmbugOfuQdEe60OvjCFD6A7yJoK/br5ixSHRyo0H+5klxpq2Y5URWD4VMSq4i5OyqCW5wy2pyWxWTNo4u+niNXGCnFrHmSf2CfFQcrEcpOaNkzFGhZET9Rkb8AEck8Pe/u13MQiaNazLmva0Yvj07Ink5bT+l45geWekCgYEA6wkSZUm/k+pvl+g0rvFF0eYZfw9z4uSUoVTTOxeMQ2NwddqqETv1K6/yIIiX8zvJcpBSYZ6pi/cSjcmB3eN84hKTJ6sDTSXr0rQCQ7weJJ9JmCqZzFIkwyflWIJ0i9imrRnV1dy2bmY/P19XB48jzdXNII1eLcWxPJD9q8QNnRMCgYEA0/9ppeX8Rc6YMNDdCu1NX3Cj8b3opajSaPAES9zEROC9BpS9LNGXZktSBfPszJ9NMFCwosLPtdEP8lyQhP8mDxWSY+VfRiMIsTKbquD9U7f+Dc0CnXkNN/e/EASoVy5t8SiJf7XoQzyxL1i3H0OARfdwz75x4tghZRz/QyN5Na0CgYA8ec4Y79vylGz4WlBL/1rmwl0nG3PHGue/h1L+rnVx1YFXYddGrfXMsu4LPvpSJL0V1FrOMYQQtpc4knPvAxzcEsl4eIFV9X7zbUvz39ClvbKzC6sdLrm02Wwi1RZl8W4Ztj8WUz9Bst9tT/vVvxgIW433ZUipYGfVXL1Y2A5krwKBgAQyDNQ0rf5vrUFiEM3TjnFyGDyDhqQoqCldfqhFsTivve3waaIGb9mdaf0mK6yNBokLP8Oo2VV+0rL4M//90LIBukjx1CXeMaDYII6ML1dFz+hUA1Y8fA98frKBdRdsms5n//igD/2sqNEID2+19UbtM7VYzoYvwtFudnrMq+xtAoGAcylzdbIfo5ik4MgxJcN4IgXddG4+gpnt26GMjPb3cLW93vPdNNslb3Fs2OIPAyi3Xs6ClkgmsRWR9h1Xrhwe0j/AyfjMRcBoO8byMGxPGZP8PD6gKYyWg30P//4IgxoCxm0swVsJ3XJNKTAs+rj2vtCutVbUozwsB1L/Ig8XvJc=",

    //异步通知地址
    'notify_url' => "http://www.laravel.com/zhubao/notify",

    //同步跳转
    'return_url' => "http://www.laravel.com/zhubao/returnpay",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA11r3S1JZ9G4+p/rfPiXtpMXLgmIYgy4e6xA+LHpa6+Nui+/6QDJs+Ua37VrKybEueHuUfodebfwusGY9bas70E23Kd+o6UjTzhZmlJu9wz0AJP2KrXf36eb4QsPA0OrMgeu7KSVEfUrw18wbWY+YKqL44wMLp08+kQZECiiPT6zYfUcWsUrZJTFKbOFaYZd11vBGsvzZyy82XUxPZjLLsBL9ED0pVeKdAq0fmOHgHYLRXmBQ2fSZ7RzPzGMikIOTO1NPWXTJS1wrv7FxVO0uzW1io8ZD6QX6sy4vwYI+Sb67iR4sNrXdv7Ew2Wt+w7EQgrm5BRpahu7NT/q8P/PXAQIDAQAB",
];