<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/5/30
 * Time: 下午5:36
 */
require __DIR__ . './../vendor/autoload.php';


$email = 'your email';
$password = 'your password';
$wechat = \Hanccc\WechatOA::getInstance($email, $password);
$wechat->sendMessage('hello1', 'oYNFUs5-y5MIxf4m2EQ230-5WRkc', 1882317807);
$wechat = \Hanccc\WechatOA::getInstance($email, $password);
$wechat->sendMessage('hello2', 'oYNFUs5-y5MIxf4m2EQ230-5WRkc', $wechat->getToken());