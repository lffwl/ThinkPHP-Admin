<?php
declare (strict_types=1);

namespace app\power\model;

use app\common\model\BaseModel;
use think\facade\Db;

class Power extends BaseModel
{

    /**
     * 重载 - pkSave
     * @param $data
     * @param null $id
     * @return bool
     */
    public function pkSave($data, $id = null)
    {
        //接口地址，小写处理
        if (!empty($data['url'])) {
            $data['url'] = strtolower($data['url']);
        }

        //是否有上级
        if (!empty($data['pid'])) {
            $data['path'] = $this->getIdPath($data['pid']);
            //添加情况需要特殊处理
            if (empty($id)) {
                //添加
                $this->startTrans();//启动事务
                if (parent::pkSave($data, $id)) {
                    $this->path = $this->path . config('power.path_split_line') . $this->id;
                    if ($this->save()) {
                        $this->commit();//提交事务
                        return true;
                    }
                }
                $this->rollback();//回滚事务
                return false;
            }
            $oldPath = $this->getIdPath($id);
            $data['path'] = $data['path'] . config('power.path_split_line') . $id;

            //是否需要更新Pid的下级path
            if ($this->getIdSubCount($id)) {
                //批量更新下级id的path
                $this
                    ->whereLike('path', '%' . $id . config('power.path_split_line') . '%')
                    ->update([
                        'path' => Db::raw('replace(`path`,"' . $oldPath . '","' . $data['path'] . '")')
                    ]);
            }

        }

        return parent::pkSave($data, $id);
    }

    /**
     * 获取id下有多少子集
     * @param $id
     * @return int
     */
    public function getIdSubCount($id)
    {
        return $this->whereLike('path', '%' . $id . config('power.path_split_line') . '%')->count();
    }

    /**
     * 获取ID的path路径
     * @param $id
     * @return mixed
     */
    public function getIdPath($id)
    {
        return $this->pkValue($id, 'path');
    }

    /**
     * 关联role
     * @return \think\model\relation\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany(Role::class, RolePower::class);
    }

    /**
     * 删除权限的时候，删除在角色表中的关联
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
     * 列表
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        return $this->select();
    }

    /**
     * 获取ids的权限集合
     * @param null $ids
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getIdsPowerMap($ids = null)
    {
        return $this
            ->field([
                'id',
                'name',
                'permission',
                'pid',
                'path',
                'menu_type',
            ])
            ->whereNotNull('permission')
            ->where(function ($q) use ($ids) {
                if (!empty($ids)) {
                    $q->whereIn('id', $ids);
                }
            })
            ->select();
    }

}
