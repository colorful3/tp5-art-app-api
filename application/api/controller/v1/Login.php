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
use app\common\model\User;

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
        if( empty( $params['phone'] ) || !is_mobile($params['phone']) ) {
            return show( config('code.error'), '手机号不合法', [],  404 );
        }
        if( empty( $params['code'] ) && empty( $params['password'] ) ) {
            return show( config('code.error'), '手机短信验证码或密码不正确', [], 404 );
        }
        if(!empty($params['code'])) {
            // 验证码登录
            // 判断code
            $code = Alidayu::getInstance()->checkSmsIdentify($params['phone']);
            // TODO 客户端加密，服务端对客户端传递过来的code解密
            if ($code != $params['code']) {
                return show(config('code.error'), '验证码错误', [], 404);
            }
        }

        // 设置token
        $token = IAuth::setAppLoginToken($params['phone']);
        // 设置要操作的data
        $data = [
            'token' => $token,
            'time_out' => strtotime('+'.config('app.login_time_out_day').' days'),
        ];
        // 查询手机号是否存在
        $user = User::get(['phone' => $params['phone']]);
        $id = 0;
        if( $user && $user->status == 1 ) {
            if( !empty($params['password'] )) {
                if( IAuth::setPassword( $params['password'] ) != $user->password ) {
                    return show(config('code.error'), '密码错误', [], 403);
                }
            }
            // 不是第一次登录，更新token
            try {
                $id = model('User')->save($data, [
                    'phone' => $params['phone']
                ]);
            } catch (\Exception $e) {
                // TODO
            }
        } else {
            if(!empty($params['code'])) {
                // 第一次登录，注册用户数据
                $data['username'] = 'CC粉丝' . $params['phone'];
                $data['status'] = 1;
                $data['phone'] = $params['phone'];
                // 插入数据
                try {
                    $id = model('User')->add($data);
                } catch (\Exception $e) {
                    // return show();
                }
            } else {
                // 密码登录
                return show(config('code.error'), '用户不存在', [], 403);
            }
        }
        // 对token进行加密
        $obj= new OpenSSLAES(config('app.aeskey'));
        $obj->encrypt($token);
        if( $id ) {
            $result = [
                'token' => $obj->encrypt($token.'||'.$id), // 和客户端工程师约定算法
            ];
            return show( config('code.success'), 'OK', $result );
        } else {
            return show( config('code.error'), '登录失败', [], 403 );
        }
    }

    /**
     * app退出登录接口
     */
    public function logout() {
        $phone = input('post.phone');
        if( empty($phone) ) {
            return show( config('code.error'), '参数传递不合法', [], 403 );
        }
        $res = model('User')->save(['token' => '', 'time_out' => 0], ['phone' => $phone]);
        if(!$res) {
            return show( config('code.error'), '退出失败', [], 403 );
        } else {
            return show(config('code.success'), '退出成功');
        }
    }

}