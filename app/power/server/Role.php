<?php
declare (strict_types=1);

namespace app\power\server;

use app\common\helper\ErrorTemplate;
use app\common\server\BaseServer;

class Role extends BaseServer
{

    /**
     * 添加之前的检查
     * @param $data
     * @param null $id
     * @return bool
     */
    public function createBeforeCheck($data, $id = null)
    {
        //验证用户名是否存在
        if ($this->checkUserNameIsExist($data['name'], $id)) {
            $this->errorMsg = ErrorTemplate::outputMsg('{{name}}：《 ' . $data['name'] . ' 》 已存在');
            return false;
        }
        return true;
    }

    /**
     * 添加角色
     * @param $data
     * @return bool
     */
    public function create($data)
    {

        //验证
        if ($this->createBeforeCheck($data)) {
            return $this->model->pkSave($data);
        }

        return false;
    }

    /**
     * 修改之前的检查
     * @param $data
     * @param $id
     * @return bool
     */
    public function updateBeforeCheck($data, $id)
    {
        //检查用户是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '角色ID：《 ' . $id . ' 》 不存在';
            return false;
        }
        return $this->createBeforeCheck($data, $id);
    }

    /**
     * 修改角色
     * @param int $id
     * @param $data
     * @return bool
     */
    public function update(int $id, $data)
    {

        //验证
        if ($this->updateBeforeCheck($data, $id)) {
            return $this->model->pkSave($data, $id);
        }

        return false;
    }

    /**
     * 检查角色ID是否存在
     * @param int $id
     * @return int
     */
    public function checkIdIsExist(int $id)
    {
        return $this->model->checkFieldIsExist('id', $id);
    }

    /**
     * 检查角色是否存在
     * @param string $name
     * @param int|null $id
     * @return int
     */
    public function checkUserNameIsExist(string $name, int $id = null)
    {
        if (empty($id)) {
            return $this->model->checkFieldIsExist('name', $name);
        }
        return $this->model->checkFieldIsExistOther('name', $name, $id, 'id');
    }

    /**
     * 删除角色
     * @param int $id
     * @return false
     */
    public function delete(int $id)
    {
        if (self::checkIdsIsSuperAdminRole($id)) {
            $this->errorMsg = '超级管理员角色不能删除';
            return false;
        }

        //检查用户是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '角色ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        //检查角色是否绑定了管理员
        if ($count = $this->model->getBindAdminCount($id)) {
            $this->errorMsg = '角色ID：《 ' . $id . ' 》 绑定了（ ' . $count . ' ）个管理员';
            return false;
        }

        return $this->model->pkDelete($id);
    }

    /**
     * 设置角色权限
     * @param int $id
     * @param $data
     * @return false
     */
    public function setPower(int $id, $data)
    {
        //检查用户是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '角色ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        //检查设置的权限是否有不存在的
        $Power = new \app\power\model\Power();
        $count = $Power->getIdsCount($data['power']);
        if ($count != count($data['power'])) {
            $this->errorMsg = '设置的权限有不存在的';
            return false;
        }

        //设置权限
        return $this->model->setPower($id, $data['power']);
    }

    /**
     * 判断角色是否是超级管理员
     * @param $ids
     * @return bool
     */
    public static function checkIdsIsSuperAdminRole($ids)
    {
        $super_admin_role_id = config('power.super_admin_role_id');
        if (is_array($ids)) {
            return in_array($super_admin_role_id, $ids);
        }
        return $ids == $super_admin_role_id;
    }

    /**
     * 角色列表
     * @param $data
     * @return mixed
     */
    public function index($data)
    {
        $data = $this->model->index($data)->visible(['power' => ['id']])->toArray();
        foreach ($data['data'] as $key => $val) {
            $data['data'][$key]['power'] = array_column($val['power'], 'id');
        }
        $data['pagination'] = $this->getPagination($data);
        return $data;
    }

    /**
     * 获取角色权限集合
     * @param $id
     * @return array
     */
    public function getPower($id)
    {
        $list = $this->model->with('power')->find($id);
        $powers = array_column($list->power->toArray(), 'pivot');
        $powers = array_column($powers, 'power_id');
        return $powers;
    }
}
