<?php
declare (strict_types=1);

namespace app\power\validate;

use app\common\validate\BaseValidate;

class Role extends BaseValidate
{
    protected $rule = [
        'name' => 'require|chsDash',
        'power' => 'require|array',
    ];

    protected $field = [
        'name' => '名称',
        'power' => '设置的权限',
    ];

    protected $scene = [
        'create' => [
            'name',
        ],
        'update' => [
            'name',
        ],
        'setPower' => [
            'power',
        ],
        'index' => [
            'name',
        ],
    ];

    public function sceneIndex()
    {
        return $this
            ->only([
                'name'
            ])
            ->remove('name', 'require');
    }

}
