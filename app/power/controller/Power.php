<?php
declare (strict_types=1);

namespace app\power\controller;

use app\common\controller\BaseController;

class Power extends BaseController
{

    /**
     * 新增权限
     * @return \think\response\Json
     */
    public function create()
    {
        $Power = new \app\power\server\Power();
        if ($Power->create($this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Power->getErrorMsg());
    }

    /**
     * 修改权限
     * @param int $id
     * @return \think\response\Json
     */
    public function update(int $id)
    {
        $Power = new \app\power\server\Power();
        if ($Power->update($id, $this->request->paramData)) {
            return $this->success();
        }
        return $this->fail($Power->getErrorMsg());
    }

    /**
     * 删除权限
     * @param int $id
     * @return \think\response\Json
     */
    public function delete(int $id)
    {
        $Power = new \app\power\server\Power();
        if ($Power->delete($id)) {
            return $this->success();
        }
        return $this->fail($Power->getErrorMsg());
    }

    /**
     * 权限列表
     * @return \think\response\Json
     */
    public function index()
    {
        $Power = new \app\power\server\Power();
        $data['list'] = $Power->index();
        $data['config'] = config('power.menu_type');
        return $this->success($data);
    }
}
