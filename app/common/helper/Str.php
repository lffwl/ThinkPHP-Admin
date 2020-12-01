<?php
declare (strict_types=1);

namespace app\common\helper;


class Str
{

    /**
     * like查询拼接
     * @param $str
     * @param string $splicing
     * @return string
     */
    public static function likeQuerySplicing($str, $splicing = '%')
    {
        return $splicing . $str . $splicing;
    }
}
