<?php
/**
 * Test.php
 * Created By Colorful
 * Date:2018/2/22
 * Time:下午12:35
 */
namespace app\api\controller;
use app\common\lib\exception\ApiException;
use think\Controller;

class Test extends Common {

    // index方法
    public function index() {
        return [
            'name' => 'hello'
        ];
    }

    // 更新方法
    public function update($id) {
        $data = input('put.');
        return $data;
    }

    // 增加方法
    public function save() {
        $data = input('post.');
        print_r($data);exit;
    }

}