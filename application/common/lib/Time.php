<?php
/**
 * Time.php
 * Created By Colorful
 * Date:2018/2/23
 * Time:上午10:36
 */
namespace app\common\lib;

class Time {

    /**
     * 获取13位时间戳
     * @return int
     */
    public static function  getTimeStamp() {
        list($t1, $t2) = explode(' ', microtime() );
        return $t2 . ceil($t1 * 1000);

    }

}