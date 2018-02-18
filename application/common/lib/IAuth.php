<?php
/**
 * IAuth.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午2:55
 * 用户校验类库
 */
namespace app\common\lib;


class IAuth {

    /**
     * 设置密码
     * @param string $pwd
     * @return string
     */
    public static function setPassword($pwd) {
        return md5($pwd . config('app.password_salt'));
    }
}
