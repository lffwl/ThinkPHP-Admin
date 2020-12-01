<?php
declare (strict_types=1);

namespace app\common\validate;

use think\Validate;

/**
 * Desc：验证器基础类
 * Author：WangLei
 * Time：2020/6/17 上午11:57
 * Class BaseValidate
 * @package app
 */
abstract class BaseValidate extends Validate
{
    /**
     * 该参数用于获取$scene参数,实现自动验证
     * @var array
     */
    public $conditions;

    /**
     * 初始化
     * BaseValidate constructor.
     */
    public function __construct()
    {
        parent::__construct();
        //获取参数
        $this->conditions = $this->scene;
    }

    /**
     * 获取验证键值对
     * @return array
     */
    public function getField()
    {
        return $this->field;
    }
}