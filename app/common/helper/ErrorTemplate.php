<?php
declare (strict_types=1);

namespace app\common\helper;

/**
 * 错误消息模板
 * Class ErrorTemplate
 * @package app\common\helper
 */
class ErrorTemplate
{

    //错误模板
    const TemplateStr = '{{';
    const TemplateEnd = '}}';

    /**
     * 错误消息模板输出
     * @param string $template
     * @param string|null $validateName
     * @param \stdClass|null $validateClass
     * @return string|string[]
     */
    public static function outputMsg(string $template, string $validateName = null, \stdClass $validateClass = null)
    {
        //验证是否存在模板
        if (self::checkIsExistTemplate($template)) {

            //验证类名处理
            $validateName = $validateName ?? request()->controller();

            //验证类是否为空
            if (empty($validateClass)) {
                $validateClass = "app\\" . app('http')->getName() . "\\validate\\" . $validateName;
            }

            //获取验证中配置的字段说明
            $validate = new $validateClass;
            $errorMsg = $validate->getField();

            //遍历替换
            foreach ($errorMsg as $key => $val) {
                $template = str_replace(self::TemplateStr . $key . self::TemplateEnd, $val, $template);
            }
        }


        return $template;

    }

    /**
     * 验证字符串是否存在模板
     * @param string $template
     * @return bool
     */
    public
    static function checkIsExistTemplate(string $template)
    {
        return (
            strpos($template, self::TemplateStr) !== false
            &&
            strpos($template, self::TemplateEnd) !== false
        );
    }

}
