<?php
declare (strict_types=1);

namespace app\power\server;

use app\common\helper\ErrorTemplate;
use app\common\helper\Secret;
use app\common\server\BaseServer;
use app\event\AdminLogin;
use thans\jwt\facade\JWTAuth;
use think\facade\Event;

class Admin extends BaseServer
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
        if ($this->checkUserNameIsExist($data['user_name'], $id)) {
            $this->errorMsg = ErrorTemplate::outputMsg('{{user_name}}：《 ' . $data['user_name'] . ' 》 已存在');
            return false;
        }
        return true;
    }

    /**
     * 添加管理员
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
            $this->errorMsg = '管理员ID：《 ' . $id . ' 》 不存在';
            return false;
        }
        return $this->createBeforeCheck($data, $id);
    }

    /**
     * 修改管理员
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
     * 检查管理员ID是否存在
     * @param int $id
     * @return int
     */
    public function checkIdIsExist(int $id)
    {
        return $this->model->checkFieldIsExist('id', $id);
    }

    /**
     * 检查用户名是否存在
     * @param string $user_name
     * @param int|null $id
     * @return int
     */
    public function checkUserNameIsExist(string $user_name, int $id = null)
    {
        if (empty($id)) {
            return $this->model->checkFieldIsExist('user_name', $user_name);
        }
        return $this->model->checkFieldIsExistOther('user_name', $user_name, $id, 'id');
    }

    /**
     * 删除管理员
     * @param int $id
     * @return false
     */
    public function delete(int $id)
    {
        //检查用户是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '管理员ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        return $this->model->pkDelete($id);
    }


    /**
     * 设置管理员角色
     * @param int $id
     * @param $data
     * @return false
     */
    public function setRole(int $id, $data)
    {
        //检查管理员是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '管理员ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        //检查设置的角色是否有不存在的
        $Role = new \app\power\model\Role();
        $count = $Role->getIdsCount($data['role']);
        if ($count != count($data['role'])) {
            $this->errorMsg = '设置的角色有不存在的';
            return false;
        }

        //设置权限
        return $this->model->setPower($id, $data['role']);
    }

    /**
     * 管理员登录
     * @param $data
     * @return array|false
     */
    public function login($data)
    {
        //检查用户是否存在
        if (!$this->checkUserNameIsExist($data['user_name'])) {
            $this->errorMsg = $this->errorMsg = ErrorTemplate::outputMsg('{{user_name}}错误');
            return false;
        }

        $info = $this->model->fieldFind('user_name', $data['user_name']);
        //检查密码是否正确
        if (!Secret::verifyPassword($data['password'], $info->password)) {
            $this->errorMsg = $this->errorMsg = ErrorTemplate::outputMsg('{{password}}错误');
            return false;
        }

        //调用管理员登录事件
        Event::trigger(new AdminLogin($info));

        return [
            'token' => JWTAuth::builder($info->visible(['id', 'user_name', 'nick_name'])->toArray()),
            'expires_time' => config('jwt.ttl')
        ];
    }

    /**
     * 管理员详情
     * @return mixed
     */
    public function info()
    {
        //获取登录的用户信息
        $payload = JWTAuth::auth();
        $id = $payload['id']->getValue();
        $info = $this->model->pkFind($id);

        //获取登录用户的角色
        $roles = $this->getRole($id);

        $Power = new Power();
        $Role = new Role();
        //验证是否是超级管理员
        if (!Role::checkIdsIsSuperAdminRole($roles)) {
            //获取登录用户的角色权限
            $powerIds = [];
            //获取管理员信息
            $role = $Role->model->pkFind($roles[0])->toArray();
            foreach ($roles as $val) {
                $powerIds = array_merge($powerIds, $Role->getPower($val));
            }
            //获取角色权限
            $role['permissions'] = $Power->getRolePower($powerIds);
        } else {
            //获取管理员信息
            $role = $Role->model->pkFind(config('power.super_admin_role_id'))->toArray();
            $role['roleId'] = $role['id'];
            //获取超级管理员权限
            $role['permissions'] = $Power->getSuperAdminRolePower();
        }

        $data = $info->hidden(['password'])->toArray();
        $data['role'] = $role;
        return $data;
    }

    /**
     * 根据ID获取角色ids
     * @param $id
     * @return array
     */
    public function getRole($id)
    {
        $list = $this->model->with('role')->find($id);
        $roles = array_column($list->role->toArray(), 'pivot');
        $roles = array_column($roles, 'role_id');
        return $roles;
    }

    /**
     * 管理员列表
     * @param $data
     * @return mixed
     */
    public function index($data)
    {
        $data = $this->model->index($data)->visible(['role' => ['id']])->toArray();
        foreach ($data['data'] as $key => $val) {
            $data['data'][$key]['role'] = array_column($val['role'], 'id');
        }
        $data['pagination'] = $this->getPagination($data);
        return $data;
    }
}
