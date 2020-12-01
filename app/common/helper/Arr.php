<?php
declare (strict_types=1);

namespace app\common\helper;

/**
 * Desc：数组扩展类
 * Class Arr
 * @package app\extend
 */
class Arr extends \think\helper\Arr
{
    /**
     * Desc：输出成json
     * @param $data
     */
    public static function outputJson($data)
    {
        header('content-type:application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * Desc：生成URl地址
     * @param $data
     * @param null $domain
     * @return string
     */
    public static function createUrl($data, $domain = null)
    {
        return urldecode($domain . (!empty($data) ? "?" . http_build_query($data) : ""));
    }

    /**
     * 规则的字符串转数组
     * @param $str
     * @param string $lRule
     * @param string $rRule
     * @return false|string[]
     */
    public static function ruleStrToArray($str, $lRule = '[', $rRule = ']')
    {
        return explode($rRule . $lRule, rtrim(ltrim($str, $lRule), $rRule));
    }

    /**
     * 生成树结构数组
     * @param $array
     * @param string $pk
     * @param string $subName
     * @return array
     */
    public static function generateTree($array, $pk = 'id', $subName = 'children')
    {
        $sorts = array_column($array, 'sort');
        array_multisort($sorts, SORT_DESC, $array);
        //第一步 构造数据
        $items = array();
        foreach ($array as $value) {
            $items[$value[$pk]] = $value;
        }

        //第二部 遍历数据 生成树状结构
        $tree = array();
        foreach ($items as $key => $value) {
            if (isset($items[$value['pid']])) {
                $items[$value['pid']][$subName][] = &$items[$key];
            } else {
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }

    /**
     * 权限处理
     * @param $array
     * @param string $subName
     * @return array
     */
    public static function permissionHandle($array, $subName = 'actionEntitySet')
    {
        $items = [];
        foreach ($array as $val) {
            if ($val['menu_type'] == 0) {
                $val['permissionId'] = $val['permission'];
                $val['permissionName'] = $val['name'];
                $val['dataAccess'] = $val['dataAccess'] ?? null;
                $val['actionList'] = $val['actionList'] ?? null;
                unset($val['permission'], $val['name'], $val['pid'], $val['path']);
                if (!empty($items[$val['id']])) {
                    $subMap = $items[$val['id']][$subName];
                    $items[$val['id']] = $val;
                    $items[$val['id']][$subName] = $subMap;
                } else {
                    $items[$val['id']] = $val;
                }
            } else {
                $items[$val['pid']][$subName][] = [
                    'action' => $val['permission'],
                    'describe' => $val['name'],
                    'defaultCheck' => false,
                ];
                $items[$val['pid']]['actionList'][] = $val['permission'];
            }
        }
        return array_values($items);
    }
}
