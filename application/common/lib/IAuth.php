<?php
/**
 * IAuth.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午2:55
 * 用户校验类库
 */
namespace app\common\lib;
use app\common\lib\Aes;
use think\Cache;


class IAuth {

    /**
     * 设置密码
     * @param string $pwd
     * @return string
     */
    public static function setPassword($pwd) {
        return md5($pwd . config('app.password_salt'));
    }

    /**
     * sign验签算法
     * @param array $data
     * @return string
     */
    public static function setSign($data=[]) {
        // 1、对数组进行按照key值进行字典排序
        ksort($data);
        // 2、生成query字符串
        $query_str = http_build_query($data);
        // 3、通过ase来加密
        // $aes_str = (new Aes())->encrypt($query_str);
        $aes_str = ( new OpenSSLAES(config('app.aeskey')) )->encrypt($query_str);
        return $aes_str;
    }

    /**
     * 验证sign
     * @param $data
     * @return bool
     */
    public static function checkSignPass($data) {
        $aes_str = ( new OpenSSLAES(config('app.aeskey')) )->decrypt($data['sign']);
        if(empty($aes_str)) {
            return false;
        }

        // did=xx&app_type=3
        parse_str($aes_str, $arr);
        if(!is_array($arr) || empty($arr['did'])
            || $arr['did'] != $data['did'] ) {
            return false;
        }

        if(!config('app_debug')) {
            // 时间有效性校验
            if (time() - ceil($arr['time'] / 1000) > config('app.app_sign_time')) {
                return false;
            }

            // 唯一性判断
            if (Cache::get($data['sign'])) {
                return false;
            }
        }
        return true;
    }
}
