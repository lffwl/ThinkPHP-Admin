<?php
declare (strict_types=1);

namespace app\common\server;

use app\common\helper\Http;

class Wx extends BaseServer
{

    /**
     * 获取微信的access_token
     * @return mixed|object|\think\App
     */
    public static function getAccessToken()
    {
        $wx_access_token = cache('wx_access_token');
        if (empty($wx_access_token) || !$wx_access_token) {
            $appid = config('wx.appid');
            $secret = config('wx.secret');
            $data = Http::get('https://api.weixin.qq.com/cgi-bin/token', [
                'grant_type' => 'client_credential',
                'appid' => $appid,
                'secret' => $secret,
            ]);
            if (!empty($data['access_token'])) {
                cache('wx_access_token', $data['access_token'], $data['expires_in'] - 1);
                $wx_access_token = cache('wx_access_token');
            }
        }

        return $wx_access_token;
    }

    /**
     * 检查文字是否含有违规内容
     * @param $content
     * @return bool
     */
    public function msgSecCheck($content)
    {
        $wx_access_token = self::getAccessToken();
        $data = Http::post('https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $wx_access_token, json_encode([
            'content' => $content,
        ]));
        if ($data['errcode'] == 0) {
            return true;
        }
        $this->errorMsg = "内容含有违法违规内容";
        return false;
    }

}