<?php
/**
 * Login.php
 * Created By Colorful
 * Date:2018/3/1
 * Time:下午11:36
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Alidayu;
use app\common\lib\IAuth;
use app\common\lib\OpenSSLAES;

class Login extends Common {

    /**
     * app登录逻辑，token算法
     */
    public function save() {
        if(!request()->isPost()) {
            return show( config('code.error'), '来路不明', [], 403 );
        }
        // 校验参数
        $params = input('post.');
        if( empty( $params['id'] ) || !is_mobile($params['id']) ) {
            return show( config('code.error'), '手机号不合法', [],  404 );
        }
        if( empty( $params['code'] ) ) {
            return show( config('code.error'), '验证码不合法', [], 404 );
        }
        // 判断code
        $code = Alidayu::getInstance()->checkSmsIdentify( $params['id'] );
        if( $code != $params['code'] ) {
            return show( config('code.error'), '验证码错误', [], 404 );
        }

        // 第一次登录，注册用户数据
        $token = IAuth::setAppLoginToken($params['id']);
        $data = [
            'token' => $token,
            'time_out' => strtotime('+'.config('app.login_time_out_day').' days'),
            'username' => 'CC粉丝'.$params['id'],
            'status' => 1,
            'phone' => $params['id'],
        ];
        // 插入数据
        try {
            $id = model('User')->add($data);
        } catch (\Exception $e) {
            // return show();
        }

        $obj = new OpenSSLAES();
        $obj->encrypt($token);
        if( $id ) {
            $result = [
                'token' => $obj->encrypt($token.'||'.$id),
                ''
            ];
        }

        //

    }
}