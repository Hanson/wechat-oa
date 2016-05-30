<?php
/**
 * Created by PhpStorm.
 * User =>hanson
 * Date =>16/5/30
 * Time =>下午5:23
 */

namespace WechatOA;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class WechatOA
{
    public $client;

    const LOGIN_URL = 'https://mp.weixin.qq.com/cgi-bin/login';
    const INDEX_URL = 'https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=';

    public function __construct($email, $password)
    {
        $this->client = new Client(['cookie' => true]);
        $this->login($email, $password);
    }

    private function login($email, $password)
    {

        $response = $this->client->request('post', self::LOGIN_URL, [
            'headers' => [
                'Host' => 'mp.weixin.qq.com',
                'Referer' => 'https://mp.weixin.qq.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'
            ],
            'form_params' => [
                'username' => $email,
                'pwd' => md5($password),
                'imgcode' => '',
                'f' => 'json'
            ],
            'cookie' => true,
        ]);
//        print_r($response->getBody()->getContents());
//        exit();
        $this->getToken($response);
    }

    private function getToken($response)
    {
        if(!preg_match('/token=(\d+)/', $response->getBody()->getContents(), $match)) {
            print_r($response->getBody()->getContents());
            throw new \Exception('get token error');
        }

        $token = $match[1];

        $this->toIndex($token);
    }

    private function toIndex($token)
    {
        $response = $this->client->request('get', self::INDEX_URL . $token);

        if($response->getStatusCode() != 200)
            throw new \Exception('go index fail!');

        print_r($response->getBody()->getContents());
    }
}