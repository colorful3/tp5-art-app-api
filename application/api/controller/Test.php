<?php
/**
 * Test.php
 * Created By Colorful
 * Date:2018/2/22
 * Time:下午12:35
 */
namespace app\api\controller;

use app\common\lib\IAuth;
use think\Controller;
use app\common\lib\Alidayu;



class Test extends Controller {


    public function smsDemo() {
        $phone = '15731666949';
        $res = Alidayu::getInstance()->setSmsIdentify($phone);
        var_dump($res);
    }

    public function smsGet() {
        $phone = '15731666949';
        $res = Alidayu::getInstance()->checkSmsIdentify($phone);
        var_dump($res);
    }

    // APP 登录和web登录的区别
    // web PHPSESSIONID app 有一个唯一的token，所有需要登录权限的请求都要带一个token。注意：token要有一个失效时间


    public function token() {
        echo IAuth::setAppLoginToken();
    }

}