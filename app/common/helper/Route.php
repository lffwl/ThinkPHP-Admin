<?php
declare (strict_types=1);

namespace app\common\helper;


class Route
{

    /**
     * 获取url中的域名
     * @param $url
     * @param bool $scheme
     * @return mixed|string
     */
    public static function getUrlDomainName($url, $scheme = true)
    {
        $arr = parse_url($url);
        if ($scheme) {
            return $arr['scheme'] . '://' . $arr['host'];
        }
        return $arr['host'];
    }

    /**
     * 检查Url是否存在域名
     * @param $url
     * @return bool
     */
    public static function checkUrlIsExistDomain($url)
    {
        $arr = parse_url($url);
        if (!empty($arr['scheme']) && !empty($arr['host'])) {
            return true;
        }
        return false;
    }

    /**
     * 获取完整的URL
     * @param $url
     * @param $link
     * @return string
     */
    public static function getCompleteUrl($url, $link)
    {
        return substr($url, 0, strrpos($url, '/') + 1) . $link;
    }

}
