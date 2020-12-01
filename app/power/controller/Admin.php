<?php
declare (strict_types=1);

namespace app\power\controller;

use app\common\controller\BaseController;

class Admin extends BaseController
{

    /**
     * 新增管理员
     * @return \think\response\Json
     */
    public function create()
    {
        $Admin = new \app\power\server\Admin();
        if ($Admin->create($this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Admin->getErrorMsg());
    }

    /**
     * 修改管理员
     * @param int $id
     * @return \think\response\Json
     */
    public function update(int $id)
    {
        $Admin = new \app\power\server\Admin();
        if ($Admin->update($id, $this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Admin->getErrorMsg());
    }

    /**
     * 删除管理员
     * @param int $id
     * @return \think\response\Json
     */
    public function delete(int $id)
    {
        $Admin = new \app\power\server\Admin();
        if ($Admin->delete($id)) {
            return $this->success();
        }
        return $this->fail($Admin->getErrorMsg());
    }

    /**
     * 设置管理员角色
     * @param int $id
     * @return \think\response\Json
     */
    public function setRole(int $id)
    {
        $Admin = new \app\power\server\Admin();
        if ($Admin->setRole($id, $this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Admin->getErrorMsg());
    }

    /**
     * 管理员登录
     * @return \think\response\Json
     */
    public function login()
    {
        $Admin = new \app\power\server\Admin();
        if ($data = $Admin->login($this->request->paramData)) {
            return $this->success($data);
        }
        return $this->fail($Admin->getErrorMsg());
    }

    /**
     * 管理员详情
     * @return \think\response\Json
     */
    public function info()
    {
        $Admin = new \app\power\server\Admin();
        $data = $Admin->info();
        return $this->success($data, null, null, 'result');
    }

    /**
     * 管理员列表
     * @return \think\response\Json
     */
    public function index()
    {
        $Admin = new \app\power\server\Admin();
        $data = $Admin->index($this->request->paramData);
        return $this->success($data['data'], $data['pagination']);
    }

}
