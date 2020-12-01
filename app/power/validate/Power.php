<?php
declare (strict_types=1);

namespace app\power\validate;

use app\common\validate\BaseValidate;

class Power extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'pid' => 'number',
        'menu_type' => 'number',
        'sort' => 'number|between:0,65535',
        'permission' => 'require',
    ];

    protected $field = [
        'name' => '接口名称',
        'url' => '接口地址',
        'pid' => '上级ID',
        'menu_type' => '菜单类型',
        'sort' => '排序',
        'permission' => '权限唯一键',
    ];

    protected $scene = [
        'create' => [
            'name',
            'url',
            'pid',
            'menu_type',
            'sort',
            'permission',
        ],
        'update' => [
            'name',
            'url',
            'pid',
            'menu_type',
            'sort',
            'permission',
        ]
    ];


    public function sceneCreate()
    {
        return $this
            ->append('menu_type', 'in:' . implode(',', array_keys(config('power.menu_type'))));
    }

}
