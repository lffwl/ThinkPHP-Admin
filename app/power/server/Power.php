<?php
declare (strict_types=1);

namespace app\power\server;

use app\common\helper\Arr;
use app\common\helper\ErrorTemplate;
use app\common\server\BaseServer;
use app\common\traits\ApiResponse;
use thans\jwt\facade\JWTAuth;

class Power extends BaseServer
{

    use ApiResponse;

    /**
     * 添加之前的检查
     * @param $data
     * @param null $id
     * @return bool
     */
    public function createBeforeCheck($data, $id = null)
    {
        //验证用户名是否存在
        if (!empty($data['url'])) {
            if ($this->checkUrlIsExist($data['url'], $id)) {
                $this->errorMsg = ErrorTemplate::outputMsg('{{url}}：《 ' . $data['url'] . ' 》 已存在');
                return false;
            }
        }

        //pid是否存在
        if (!empty($data['pid'])) {
            if (!$this->checkIdIsExist((int)$data['pid'])) {
                $this->errorMsg = ErrorTemplate::outputMsg('{{pid}}：《 ' . $data['pid'] . ' 》 不存在');
                return false;
            }
        }

        return true;
    }

    /**
     * 添加权限
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

        //上级id是否为空
        if (!empty($data['pid'])) {
            if ($data['pid'] == $id) {
                $this->errorMsg = ErrorTemplate::outputMsg('{{pid}}：《 ' . $data['pid'] . ' 》 不能等于权限ID');
                return false;
            }
        }

        //检查权限ID是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '权限ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        return $this->createBeforeCheck($data, $id);
    }

    /**
     * 修改权限
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
     * 检查权限ID是否存在
     * @param int $id
     * @return int
     */
    public function checkIdIsExist(int $id)
    {
        return $this->model->checkFieldIsExist('id', $id);
    }

    /**
     * 检查接口地址是否存在
     * @param string $url
     * @param int|null $id
     * @return int
     */
    public function checkUrlIsExist(string $url, int $id = null)
    {
        if (empty($id)) {
            return $this->model->checkFieldIsExist('url', $url);
        }
        return $this->model->checkFieldIsExistOther('url', $url, $id, 'id');
    }

    /**
     * 删除权限
     * @param int $id
     * @return false
     */
    public function delete(int $id)
    {
        //验证
        if ($this->deleteBeforeCheck($id)) {
            return $this->model->pkDelete($id);
        }
        return false;
    }

    /**
     * 删除前检查
     * @param $id
     * @return bool
     */
    public function deleteBeforeCheck($id)
    {
        //检查用户是否存在
        if (!$this->checkIdIsExist($id)) {
            $this->errorMsg = '权限ID：《 ' . $id . ' 》 不存在';
            return false;
        }

        //验证权限ID下是否有子集
        if ($number = $this->model->getIdSubCount($id)) {
            $this->errorMsg = '权限ID:（ ' . $id . ' ）下有《 ' . $number . ' 》 个子权限';
            return false;
        }
        return true;
    }

    /**
     * 列表
     * @return array
     */
    public function index()
    {
        $data = $this->model->index()->toArray();
        $data = Arr::generateTree($data);
        return $data;
    }

    /**
     * 获取角色权限
     * @param $powerIds
     * @return array
     */
    public function getRolePower($powerIds)
    {
        $data = $this->model->getIdsPowerMap($powerIds)->toArray();
        $data = Arr::permissionHandle($data, 'actionEntitySet');
        return $data;
    }

    /**
     * 超级管理员角色权限
     * @return array
     */
    public function getSuperAdminRolePower()
    {
        $data = $this->model->getIdsPowerMap()->toArray();
        $data = Arr::permissionHandle($data, 'actionEntitySet');
        return $data;
    }

    /**
     * 检查是否有权限访问接口
     * @param $url
     * @return \think\response\Json
     */
    public function checkAdminPower($url)
    {

        $url = strtolower($url);

        //获取登录的用户信息
        $payload = JWTAuth::auth();
        $id = $payload['id']->getValue();

        //获取登录用户的角色
        $Admin = new Admin();
        $roles = $Admin->getRole($id);
        $Role = new Role();
        //验证是否是超级管理员
        if (!Role::checkIdsIsSuperAdminRole($roles)) {
            //获取登录用户的角色权限
            $powerIds = [];

            foreach ($roles as $val) {
                $powerIds = array_merge($powerIds, $Role->getPower($val));
            }

            if (!in_array($this->model->fieldValue('url', $url, 'id'), $powerIds)) {
                return $this->fail('没有权限', true);
            }

        }
    }
}
