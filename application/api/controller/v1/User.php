<?php
/**
 * User.php
 * Created By Colorful
 * Date:2018/3/2
 * Time:下午12:16
 */
namespace app\api\controller\v1;


use app\common\lib\IAuth;
use app\common\lib\OpenSSLAES;
use app\common\model\User as UserModel;

class User extends AuthBase {

    /**
     * 获取用户信息
     * 此类数据非常隐私，需要加密处理
     */
    public function read() {
        $obj = new OpenSSLAES(config('app.aeskey'));
        $data = $obj->encrypt($this->user);
        return show(config('code.success'), 'OK', $data);
    }

    /**
     * 用户基本信息修改
     * 也包含用户密码的设置和修改
     */
    public function update() {
        $params = input('param.');
        // todo 校验数据
        $data = [];
        if( !empty($params['image']) ) {
            $data['image'] = $params['image'];
        }
        if( !empty($params['username']) ) {
            $data['username'] = $params['username'];
        }
        if( !empty($params['signature']) ) {
            $data['signature'] = $params['signature'];
        }
        if( !empty($params['password']) ) {
            // todo ase加密
            $data['password'] = IAuth::setPassword($params['password']);
        }

        if( empty($data) ) {
            return show( config('code.error'), '数据不合法', [], 404 );
        }

        // 入库操作
        try {
            $id = model('User')->save($data, [ 'id' => $this->user->id ]);
            if( $id ) {
                return show( config('code.success'), 'OK', [], 202 );
            } else {
                return show( config('code.error'), '更新失败', [], 403 );
            }
        } catch (\Exception $e) {
            return show( config('code.error'), $e->getMessage(), [], 500 );
        }
    }

    /**
     * 检验用户昵称是否重复接口
     */
    public function checkUsername() {
        $username = trim( input('param.username') );
        if( !$username ) {
            return show( config('code.error'), '参数传递不正确', [], 404 );
        }
        $count = model('User')->where(['username' => $username])->count();
        if( $count > 0 ) {
            return show( config('code.error'), '用户名已存在，请换一个试试吧', [], 403 );
        } else {
            return show( config('code.success'), 'OK' );
        }
    }

}