<?php
/**
 * Created by PhpStorm.
 * User: HanSon
 * Date: 2017/2/14
 * Time: 16:26
 */

namespace Hanson\MpWeixin\Utils;


class Cookies
{

    public static function parseCookies($cookies)
    {
        if(!$cookies || !is_array($cookies)){
            return false;
        }

        $result = [];

        foreach ($cookies as $key => $cookie) {
            $result[$key]['Name'] = $cookie['name'];
            $result[$key]['Value'] = $cookie['value'];
            $result[$key]['Domain'] = $cookie['domain'];
            $result[$key]['Path'] = $cookie['path'];
            $result[$key]['Max-Age'] = $result['Expires'] = $cookie['expirationDate'] ?? null;
            $result[$key]['Secure'] = $result['secure'] ?? null;
            $result[$key]['HttpOnly'] = $cookie['httpOnly'];
        }

        return $result;
    }

}