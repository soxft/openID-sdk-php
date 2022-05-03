<?php

/**
 * SDK of XopenID 
 * @version v1.0
 * @author xcsoft <contact@xcsoft.top>
 */

namespace soxft;

class OpenIdSdk
{
    /**
     * @var string api
     */
    private static $api = 'https://9420.ltd/';

    /**
     * @var string
     */
    private $appid;

    /**
     * @var string
     */
    private $app_secret;

    /**
     * @param string $appid 应用ID
     * @param string $app_secret 应用密钥
     */
    function __construct(string $appid, string $app_secret)
    {
        $this->appid = $appid;
        $this->app_secret = $app_secret;
    }

    /**
     * 跳转至授权界面
     * 
     * @param string $redirect_uri 回调链接
     * @param void
     */
    public function jump(string $redirect_uri): void
    {
        header("Location: " . self::$api . 'api/v1/login?appid=' . $this->appid . '&redirect_uri=' . urlencode($redirect_uri));
    }

    /**
     * 请求用户信息
     * 
     * @param string $token GET参数中的token
     * @return array 用户信息
     */
    public function getUserInfo(string $token): array
    {
        if (empty($token)) return ['code' => 101, 'msg' => 'token cant be empty', 'data' => []];
        $param = [
            'appid' => $this->appid,
            'app_secret' => $this->app_secret,
            'token' => $token,
        ];
        $res = $this->httpPost(self::$api . 'api/v1/info', $param);
        if (empty($res)) return ['code' => 102, 'msg' => 'http request error', 'data' => []];
        return json_decode($res, true);
    }

    private function httpPost(string $url, array $param): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}
