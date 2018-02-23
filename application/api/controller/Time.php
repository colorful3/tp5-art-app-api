<?php
/**
 * Time.php
 * Created By Colorful
 * Date:2018/2/23
 * Time:上午11:44
 */
namespace app\api\controller;
use think\Controller;

class Time extends Controller {

    // 服务器客户端时间一致性解决方案
    public function index() {
        return show(1, 'ok', time() );
    }

}
