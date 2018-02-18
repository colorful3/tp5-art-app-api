<?php
/**
 * Login.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午2:21
 */
namespace app\admin\controller;
use think\Controller;
use app\common\lib\IAuth;
use think\Exception;

class Login extends Base {

    public function _initialize() {
        return true;
    }

    public function index() {
        // 如果已经登录，需要跳转到后台首页
        $is_login = $this->isLogin();
        if( $is_login ) {
            return $this->redirect('index/index');
        } else {
            return $this->fetch();
        }
    }

    /**
     * 后台登录校验
     */
    public function check() {
        if( request()->isPost() ) {
            // 接收客户端数据
            $data = input('post.');
            // 判断验证码是否正确
            if ( !captcha_check($data['code']) ) {
                $this->error('验证码不正确');
            }
            // 校验数据
            $validate = validate('Login');
            if( !$validate->check($data) ) {
                $this->error( $validate->getError() );
            }
            $user = "";
            try {
                // 根据USERNAME查询数据
                $user = model('AdminUser')->get(['username' => $data['username']]);
            } catch (Exception $e) {
                $this->error( $e->getMessage() );
            }
            if ( !$user || $user->status != config('code.status_normal') ) {
                $this->error('该用户不存在');
            }

            // 校验密码
            if (IAuth::setPassword($data['password']) != $user->password) {
                $this->error('密码不正确');
            }

            // 1、更新数据库 last_login_time, last_login_ip
            // 2、保存用户信息到session中
            $udata = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip()
            ];
            try {
                model('AdminUser')->save($udata, ['id' => $user->id]);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
            session( config('admin.session_user'), $user, config('admin.session_user_scope') );
            $this->success('登录成功', url('index/index') );

        } else {
            $this->error('请求不合法');
        }
    }

}
