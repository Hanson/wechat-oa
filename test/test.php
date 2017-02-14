<?php


$html = file_get_contents(__DIR__ . '/user.html');

preg_match('/wx.cgiData=(.+)seajs.use/si', $html, $matches);
//print_r($matches);
$users = substr($matches[1], 0, -7);
$users = str_replace([' ', "
"], ['', ''], $users);
file_put_contents(__DIR__ . '/user-back.html', $users);
//echo $users;
//print_r(json_decode($users, true));
//print_r(json_last_error_msg());