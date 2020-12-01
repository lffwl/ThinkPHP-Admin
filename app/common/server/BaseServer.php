<?php
declare (strict_types=1);

namespace app\common\server;

class BaseServer
{
    //server配套的model
    protected $model;

    /**
     * 错误信息
     * @var
     */
    protected $errorMsg;

    /**
     * 初始化
     * BaseServer constructor.
     * @param bool $initModel
     * @param \stdClass|null $model
     */
    public function __construct($initModel = true, \stdClass $model = null)
    {
        $modelClass = str_replace('server', 'model', get_called_class());
        //是否初始化model
        if ($initModel) {
            if (class_exists($modelClass)) {
                $this->model = new $modelClass;
            } else {
                $this->model = $model;
            }
        }

        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * 组装分页
     * @param $data
     * @return array
     */
    public function getPagination($data)
    {
        return [
            "total" => $data['total'],
            "pageSize" => $data['per_page'],
            "current" => $data['current_page'],
        ];
    }
}
