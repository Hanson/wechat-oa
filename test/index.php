<?php

require __DIR__ . '/../vendor/autoload.php';

$weixin = new \Hanson\MpWeixin\Weixin('chenhaihua@gzdmc.net', 'gzdmc2015', __DIR__);
echo $homePage = $weixin->getHomePage();
file_put_contents(__DIR__ . '/home.html', $homePage);
preg_match('/总用户数/', $homePage, $matches);
print_r($matches);