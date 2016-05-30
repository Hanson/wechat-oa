# wechat-oa
登录微信公众号并可主动发送消息

# install

composer require hanccc/wechat-oa

# usage

```
$wechat = \Hanccc\WechatOA::getInstance($email, $password);
$wechat->sendMessage('hello', 'openid');
```

```
//或者可以先把token存储起来减少登录时所耗费的时间
$wechat = \Hanccc\WechatOA::getInstance($email, $password);
$token = $wechat->getToken
$wechat->sendMessage('hello', 'openid', $wechat->getToken());
```
