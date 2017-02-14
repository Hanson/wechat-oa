<?php
/**
 * Created by PhpStorm.
 * User: HanSon
 * Date: 2017/2/14
 * Time: 10:24
 */

namespace Hanson\MpWeixin;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;

class Weixin
{

    const LOGIN_URL = 'https://mp.weixin.qq.com/cgi-bin/bizlogin?action=startlogin';

    const QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/loginqrcode';

    private $username;

    private $pwd;

    private $client;

    public function __construct($username, $pwd, $path = null)
    {
        $path = $path ? : sys_get_temp_dir();
        $this->username = $username;
        $this->pwd = $pwd;

        $cookieJar = new FileCookieJar($path . '/mp-weixin.txt');
        $this->client = new Client(['cookies' => $cookieJar]);
    }

    public function login()
    {
        $response = $this->client->post(static::LOGIN_URL, [
            'headers' => [
                'Host' => 'mp.weixin.qq.com',
                'Referer' => 'https://mp.weixin.qq.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'
            ],
            'form_params' => [
                'username' => $this->username,
                'pwd' => md5(substr($this->pwd, 0, 16)),
                'imgcode' => '',
                'f' => 'json'
            ]
        ]);

        print_r($response->getBody()->getContents());
        
//        $this->getQrCode();
    }

    private function askCode()
    {
        $response = $this->client->get(static::QRCODE_URL, [
            'query' => [
                'action' => 'ask',
                'token' => '',
                'lang' => 'zh_CN',
                'f' => 'json',
                'ajax' => 1,
                'random' => '0.10842209632312327'
            ]
        ]);

        echo $response->getBody()->getContents() . "\n";
        $this->getQrCode();
    }

    public function getQrCode()
    {
        $response = $this->client->get(static::QRCODE_URL, [
            'query' => [
                'action' => 'getqrcode',
                'param' => 4300,
                'rd' => 909
            ]
        ]);

        echo $response->getBody()->getContents();
    }

    public function getHomePage()
    {
        $response = $this->client->get('https://mp.weixin.qq.com/cgi-bin/home', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
                'Host' => 'mp.weixin.qq.com',
                'Connection' => 'keep-alive',
                'Cache-Control' => 'no-cache',
                'Accept-Encoding' => 'gzip, deflate, sdch, br',
                'Accept-Language' => 'zh-CN,zh;q=0.8,en;q=0.6',
                'Upgrade-Insecure-Requests' => 1,
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
            ],
            'query' => [
                't' => 'home/index',
                'lang' => 'zh_CN',
                'token' => '297861009'
            ]
        ]);

        return $response->getBody()->getContents();
    }

}