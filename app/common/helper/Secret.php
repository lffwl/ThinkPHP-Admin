<?php
declare (strict_types=1);

namespace app\common\helper;

/**
 * 秘钥处理
 * Class Secret
 * @package app\common\helper
 */
class Secret
{

    /**
     * 生成密码
     * @param string $password 需要加密的密码
     * @return false|string|null
     */
    public static function createPassword(string $password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 验证密码是否正确
     * @param string $password 输入的密码
     * @param string $hashStr hash字符串
     * @return bool
     */
    public static function verifyPassword(string $password, string $hashStr)
    {
        return password_verify($password, $hashStr);
    }

}
