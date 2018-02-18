<?php
/**
 * Base.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午3:40
 * @desc 后台基础类库
 */
namespace app\admin\controller;
use think\Controller;


class Base extends Controller {

    // 当前页
    public $page = '';

    // 每页显示条数
    public $page_size = '';
    // 分页的偏移量
    public $offset = 0;

    /**
     * 初始化方法
     */
    public function _initialize() {
        // 判断后台用户登录态
        $is_login = $this->isLogin();
        if( !$is_login ) {
            $this->redirect( 'login/index' );
        }
    }

    /**
     * 判定用户登录态
     * @return bool
     */
    public function isLogin() {
        $user = session( config('admin.session_user'), '', config('admin.session_user_scope') );
        if( $user && $user->id ) {
            return true;
        }
        return false;
    }


    /**
     * 获取分页的相关参数
     * @param $data
     */
    public function getPageParams($data) {
        $this->page = !empty($data['page']) ? intval($data['page']) : 1;
        $this->page_size = !empty($data['page_size']) ? $data['page_size'] : config('paginate.list_rows');
        $this->offset = ( $this->page - 1 ) * $this->page_size;
    }

}
