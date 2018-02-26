<?php
/**
 * Test.php
 * Created By Colorful
 * Date:2018/2/22
 * Time:下午12:35
 */
namespace app\api\controller;

use think\Controller;
use app\common\lib\Alidayu;



class Test extends Controller {




    public function smsDemo() {
        $phone = '***';
        $res = Alidayu::getInstance()->setSmsIdentify($phone);
        var_dump($res);
    }

}