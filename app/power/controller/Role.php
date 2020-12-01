<?php
declare (strict_types=1);

namespace app\power\controller;

use app\common\controller\BaseController;

class Role extends BaseController
{

    /**
     * 新增角色
     * @return \think\response\Json
     */
    public function create()
    {
        $Role = new \app\power\server\Role();
        if ($Role->create($this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Role->getErrorMsg());
    }

    /**
     * 修改角色
     * @param int $id
     * @return \think\response\Json
     */
    public function update(int $id)
    {
        $Role = new \app\power\server\Role();
        if ($Role->update($id, $this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Role->getErrorMsg());
    }

    /**
     * 删除角色
     * @param int $id
     * @return \think\response\Json
     */
    public function delete(int $id)
    {
        $Role = new \app\power\server\Role();
        if ($Role->delete($id)) {
            return $this->success();
        }
        return $this->fail($Role->getErrorMsg());
    }

    /**
     * 设置角色权限
     * @param int $id
     * @return \think\response\Json
     */
    public function setPower(int $id)
    {
        $Role = new \app\power\server\Role();
        if ($Role->setPower($id, $this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Role->getErrorMsg());
    }

    /**
     * 角色列表
     * @return \think\response\Json
     */
    public function index()
    {
        $Role = new \app\power\server\Role();
        $data = $Role->index($this->request->paramData);
        return $this->success($data['data'], $data['pagination']);
    }
}
