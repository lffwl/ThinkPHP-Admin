<?php
declare (strict_types=1);

namespace app\power\model;

use app\common\helper\Secret;
use app\common\model\BaseModel;

class Admin extends BaseModel
{

    /**
     * 重载 - pkSave
     * @param $data
     * @param null $id
     * @return bool
     */
    public function pkSave($data, $id = null)
    {
        //密码加密
        if (!empty($data['password'])) {
            $data['password'] = Secret::createPassword($data['password']);
        } else {
            unset($data['password']);
        }
        return parent::pkSave($data, $id);
    }

    /**
     * 关联role
     * @return \think\model\relation\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * 设置管理员角色关联
     * @param $id
     * @param $role
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setPower($id, $role)
    {
        $admin = $this->find($id);
        $this->startTrans();

        //删除关联
        $admin->role()->detach();

        //重新建立关联
        if ($admin->role()->attach($role)) {
            $this->commit();
            return true;
        }

        $this->rollback();
        return false;
    }

    /**
     * 重载删除，删除管理员时删除管理员角色表
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function pkDelete($id)
    {
        $role = $this->find($id);
        $this->startTrans();
        //删除关联
        $role->role()->detach();
        //删除角色
        if (parent::pkDelete($id)) {
            $this->commit();
            return true;
        }

        $this->rollback();
        return false;
    }

    /**
     * 更新登录时间
     * @return bool
     */
    public function saveLoginTime()
    {
        return $this->save(['last_login_time' => time()]);
    }

    /**
     * 列表
     * @param $data
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function index($data)
    {
        return $this
            ->field([
                'id',
                'user_name',
                'nick_name',
                'create_time',
                'last_login_time',
            ])
            ->with('role')
            ->paginate([
                'list_rows' => input('limit', 20),
                'page' => input('page', 1),
            ]);
    }
}
