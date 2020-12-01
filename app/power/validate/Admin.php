<?php
declare (strict_types=1);

namespace app\power\validate;

use app\common\validate\BaseValidate;

class Admin extends BaseValidate
{
    protected $rule = [
        'user_name' => 'require|alpha',
        'nick_name' => 'require|chs',
        'password' => 'require|min:6',
        'role' => 'require|array',
    ];

    protected $field = [
        'user_name' => '用户名',
        'nick_name' => '姓名',
        'password' => '密码',
        'role' => '设置的角色',
    ];

    protected $scene = [
        'create' => [
            'user_name',
            'nick_name',
            'password',
        ],
        'update' => [
            'user_name',
            'nick_name',
            'password',
        ],
        'setRole' => [
            'role',
        ],
        'login' => [
            'user_name', 'password'
        ],
    ];


    public function sceneUpdate()
    {
        return $this
            ->only([
                'user_name',
                'nick_name',
                'password',
            ])
            ->remove('password', 'require');
    }

}
