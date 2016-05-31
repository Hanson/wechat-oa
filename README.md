# wechat-oa
模拟登录微信公众号并可主动向24小时内有消息互动的用户主动发送消息

# install

composer require hanccc/wechat-oa

# usage

## 普通模式

```
$wechat = new \Hanccc\WechatOA($email, $password);
```

## 多例模式

```
$wechat = \Hanccc\WechatOA::getInstance($email, $password);
```

### 发送消息
```
$wechat->sendMessage('hello', 'openid');
```

```
//或者可以先把token存储起来减少登录时所耗费的时间
$token = $wechat->getToken
$wechat->sendMessage('hello', 'openid', $wechat->getToken());
```
