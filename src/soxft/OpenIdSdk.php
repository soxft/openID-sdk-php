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
    private static $api = 'https://9420.ltd/api';

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
        header("Location: " . self::$api . '/v1/login?appid=' . $this->appid . '&redirect_uri=' . urlencode($redirect_uri));
    }

    /**
     * 获取授权链接
     *
     * @param string $redirect_uri
     * @return string
     */
    public function getAuthUrl(string $redirect_uri): string
    {
        return self::$api . '/v1/login?appid=' . $this->appid . '&redirect_uri=' . urlencode($redirect_uri);
    }

    /**
     * 请求用户信息
     * 
     * @param string $token GET参数中的token
     * @return array 用户信息
     */
    public function getUserInfo(string $token): array
    {
        if ($token === "") return $this->response(false, 'token is empty', []);

        $param = [
            'appid' => $this->appid,
            'app_secret' => $this->app_secret,
            'token' => $token,
        ];

        // http request
        $res = $this->httpPost('/v1/info', $param);
        if ($res === "" || $res === null) return $this->response(false, 'http request error', []);

        // unmarshal json
        $res_unmarshal = json_decode($res, true);

        if ($res_unmarshal['success'] === false) return $this->response(false, $res_unmarshal['msg'], []);
        return $this->response(true, 'success', $res_unmarshal['data']);
    }

    private function httpPost(string $url, array $param): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$api . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function response(bool $success, string $msg, array $data): array
    {
        return [
            'success' => $success,
            'msg' => $msg,
            'data' => $data,
        ];
    }
}
