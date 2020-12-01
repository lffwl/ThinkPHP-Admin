<?php
declare (strict_types=1);

namespace app\common\traits;

use app\common\helper\Arr;

/**
 * Desc：接口输出类
 * Trait ApiResponse
 */
trait ApiResponse
{

    /**
     * Desc：响应正确输出
     * @param null $data
     * @param null $pagination
     * @param string $msg
     * @param string $name
     * @return \think\response\Json
     */
    public function success($data = null, $pagination = null, $msg = '成功', $name = 'data')
    {
        //检查数据库数据是否已经转为数组
        if (is_object($data)) {
            $data = $data->toArray();
        }
        $response = [
            'status' => 'success',
            $name => $data,
            'msg' => $msg,
        ];
        if ($pagination !== null) {
            $response['pagination'] = $pagination;
        }
        return json($response);
    }

    /**
     * 响应失败输出
     * 失败输出不用json方法的原因，在base控制器中不能使用return
     * @param null $msg
     * @param false $isBase
     * @return \think\response\Json
     */
    public function fail($msg = null, $isBase = false)
    {
        $msg = $msg ?? '失败！';
        $response = [
            'status' => 'fail',
            'msg' => $msg,
        ];
        if ($isBase) {
            Arr::outputJson($response);
        }
        return json($response);
    }

}
