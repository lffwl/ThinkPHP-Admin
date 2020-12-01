<?php
declare (strict_types=1);

namespace app\power\model;

use app\common\helper\Str;
use app\common\model\BaseModel;

class Role extends BaseModel
{

    /**
     * 关联power
     * @return \think\model\relation\BelongsToMany
     */
    public function power()
    {
        return $this->belongsToMany(Power::class);
    }

    /**
     * 关联admin
     * @return \think\model\relation\BelongsToMany
     */
    public function admin()
    {
        return $this->belongsToMany(Admin::class, AdminRole::class);
    }

    /**
     * 设置角色权限关联
     * @param $id
     * @param $power
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setPower($id, $power)
    {
        $role = $this->find($id);
        $this->startTrans();

        //删除关联
        $role->power()->detach();

        //重新建立关联
        if ($role->power()->attach($power)) {
            $this->commit();
            return true;
        }

        $this->rollback();
        return false;
    }

    /**
     * 重载删除，解除权限和角色的关系
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
        $role->power()->detach();
        //删除角色
        if (parent::pkDelete($id)) {
            $this->commit();
            return true;
        }

        $this->rollback();
        return false;
    }

    /**
     * 获取角色绑定的管理员数量
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getBindAdminCount($id)
    {
        $role = $this->with('admin')->find($id);
        return $role->admin->count();
    }

    /**
     * 角色列表
     * @param $data
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function index($data)
    {
        return $this
            ->field([
                'id',
                'name',
                'create_time',
            ])
            ->with('power')
            ->where(function ($q) use ($data) {
                if (!empty($data['name'])) {
                    $q->whereLike('name', Str::likeQuerySplicing($data['name']));
                }
            })
            ->paginate([
                'list_rows' => input('limit', 20),
                'page' => input('page', 1),
            ]);
    }
}
