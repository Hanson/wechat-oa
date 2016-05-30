<?php
/**
 * Created by PhpStorm.
 * User =>hanson
 * Date =>16/5/30
 * Time =>下午5:23
 */

namespace Hanccc;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Psr7\Response;

class WechatOA
{
    private $client;

    private $token;

    public $userList;

    static $timestamp;

    static $instances;

    const LOGIN_URL = 'https://mp.weixin.qq.com/cgi-bin/login';
    const INDEX_URL = 'https://mp.weixin.qq.com/cgi-bin/home?t=home/index&lang=zh_CN&token=%s';
    const FAKE_ID_URL = 'https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=%s&lang=zh_CN';
    const MESSAGE_URL = 'https://mp.weixin.qq.com/cgi-bin/message?t=message/list&action=&keyword=&offset=0&count=%d&day=7&filterivrmsg=&token=%s&lang=zh_CN';
    const SEND_URL = 'https://mp.weixin.qq.com/cgi-bin/singlesend?t=ajax-response&f=json&token=%s&lang=zh_CN';

    public function __construct($email, $password)
    {
        $jar = new SessionCookieJar('session_id', true);
        $this->client = new Client(['cookies' => $jar]);
        $this->login($email, $password);
    }

    public static function getInstance($email, $password)
    {
        if (time() - self::$timestamp < 7200 && isset(self::$instances[$email])) {
            return self::$instances[$email];
        } else {
            self::$timestamp = time();
            $instance = new WechatOA($email, $password);
            self::$instances[$email] = $instance;
            return $instance;
        }
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
        ]);
        $this->parseToken($response);
        $this->getUserEachPage();
    }

    /**
     * @param $response Response
     * @throws \Exception
     */
    private function parseToken($response)
    {
        if (!preg_match('/token=(\d+)/', $response->getBody()->getContents(), $match)) {
            throw new \Exception('get token error');
        }

        $this->token = $match[1];
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * 获取首页response
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    private function toIndex()
    {
        $response = $this->client->request('get', sprintf(self::INDEX_URL, $this->token));

        if ($response->getStatusCode() != 200)
            throw new \Exception('go index fail!');

        return $response;
    }

    /**
     * @return mixed 公众号的fakeId
     * @throws \Exception
     */
    private function getFakeId()
    {
        $response = $this->client->request('get', sprintf(self::FAKE_ID_URL, $this->token));

        if (!preg_match('/fakeid=(\d{10})/', $response->getBody()->getContents(), $match))
            throw new \Exception('get fakeid error');

        return $match[1];
    }

    private function getUserEachPage()
    {
        $response = $this->client->request('get', sprintf(self::MESSAGE_URL, 20, $this->token));

        $content = $response->getBody()->getContents();
        if (!preg_match('/list : \((.+)\)\.msg_item/', $content, $data))
            throw new \Exception('no msg data');

        $data = json_decode($data[1], true)['msg_item'];
        if (count($data) === 0)
            throw new \Exception('no more new messages');

        foreach ($data as $user) {
            if (time() - $user['date_time'] > 172800)
                continue;

            $this->userList[$user['fakeid']] = $user['nick_name'];
        }
    }

    /**
     * @param $message String 发送的消息
     * @param $openId String 用户openId
     * @param null $token String 网页token
     * @return bool
     */
    public function sendMessage($message, $openId, $token = null)
    {
        $token = $token ?: $this->token;

        $response = $this->client->request('post', self::SEND_URL, [
            'headers' => [
                'Host' => 'mp.weixin.qq.com',
                'Origin' => 'https://mp.weixin.qq.com',
                'Referer' => sprintf('https://mp.weixin.qq.com/cgi-bin/singlesendpage?t=message/send&action=index&tofakeid=%s&token=%s&lang=zh_CN', $openId, $token),
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'
            ],
            'form_params' => [
                'token' => $token,
                'lang' => 'zh_CN',
                'f' => 'json',
                'ajax' => '1',
                'random' => '0.4469808244612068',
                'type' => '1',
                'content' => $message,
                'tofakeid' => $openId,
                'imgcode' => ''
            ],
        ]);

        if (!$response->getStatusCode() == 200)
            return false;

        $response = json_decode($response->getBody()->getContents(), true);
        return $response['base_resp']['err_msg'] === 'ok' ? true : false;
    }


}