<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/5/30
 * Time: 下午5:36
 */

require __DIR__ . './../vendor/autoload.php';


$email = 'h@hanc.cc';
$password = 'hanson1994';
$wechat = new \WechatOA\WechatOA($email, $password);