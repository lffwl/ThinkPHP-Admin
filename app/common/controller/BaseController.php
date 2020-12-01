<?php
declare (strict_types=1);

namespace app\common\controller;

use app\common\traits\ApiResponse;
use think\App;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    use ApiResponse;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        //自动验证实现
        $this->autoValidate();

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }

    /**
     * 自动验证
     */
    private function autoValidate()
    {
        //获取当前的验证类
        $validateClass = "app\\" . app('http')->getName() . "\\validate\\" . $this->request->controller();
        //验证，验证类是否存在
        if (class_exists($validateClass)) {
            $validate = new $validateClass;
            //获取当前访问的方法
            $action = $this->request->action();
            if (isset($validate->conditions[$action])) {
                //获取请求的参数过滤不需要的参数
                $this->request->paramData = $this->request->only($validate->conditions[$action]);
                if (!$validate->scene($action)->check($this->request->paramData)) {
                    $this->fail($validate->getError(), true);
                }
            }
        }
    }

}
