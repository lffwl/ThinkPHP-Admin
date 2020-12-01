<?php
declare (strict_types=1);

namespace app\common\helper;


use think\exception\ErrorException;

class Http
{

    /**
     * GET请求
     * @param $url
     * @param array $param
     * @param bool $toArray
     * @param array $header
     * @return bool|mixed|string
     */
    public static function get($url, $param = [], $toArray = true, $header = [])
    {

        //参数追加
        if (!empty($param)) {
            $url = $url . "?" . http_build_query($param);
        }

        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 超时设置,以秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 1);

        // 超时设置，以毫秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //执行命令
        $data = curl_exec($curl);
        // 显示错误信息
        if (curl_error($curl)) {
            print "Error: " . curl_error($curl);
            exit;
        }
        curl_close($curl);

        // json - array
        if ($toArray) {
            $data = json_decode($data, true);
        }

        return $data;
    }

    /**
     * POST请求
     * @param $url
     * @param null $param
     * @param bool $toArray
     * @param array $header
     * @return bool|mixed|string
     */
    public static function post($url, $param = null, $toArray = true, $header = [])
    {

        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 超时设置
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        // 超时设置，以毫秒为单位
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        // 设置请求头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        //执行命令
        $data = curl_exec($curl);
        // 显示错误信息
        if (curl_error($curl)) {
            print "Error: " . curl_error($curl);
            exit;
        }
        curl_close($curl);

        // json - array
        if ($toArray) {
            $data = json_decode($data, true);
        }

        return $data;

    }

}
