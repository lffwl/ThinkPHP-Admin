<?php
declare (strict_types=1);

namespace app\event;

use app\power\model\Admin;
use think\Event;

class AdminLogin
{
    public $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;

    }

    /**
     * 登录事件监听
     * @param $event
     */
    public function onAdminLogin($event)
    {
        //调用定义的更新登录时间事件
        $event->saveLoginTime();
    }

    /**
     * 事件订阅
     * @param Event $event
     */
    public function subscribe(Event $event)
    {
        //登录事件
        $event->listen('AdminLogin', [$this, 'onAdminLogin']);
    }

    /**
     * 更新登录时间
     * @return bool
     */
    public function saveLoginTime()
    {
        return $this->admin->saveLoginTime();
    }
}
