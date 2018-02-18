<?php
namespace app\admin\controller;


class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    public function welcome() {
        return 'hello Colorful C ADMIN!';
    }

    /**
     * 退出登录
     * 1、清空session
     * 2、跳转到登录页面
     */
    public function logout() {
        session(null, config('admin.session_user_scope') );
        $this->redirect(url('login/index'));
    }
}
