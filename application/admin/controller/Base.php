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
use think\Exception;


class Base extends Controller {

    // 当前页
    public $page = '';

    // 每页显示条数
    public $page_size = '';
    // 分页的偏移量
    public $offset = 0;
    // 模型名称
    public $model = '';

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

    /**
     * 通用删除方法
     */
    public function delete() {
        $id = input('id', 0);
        if( !intval($id) ) {
            return $this->result('', 0, 'ID不合法');
        }
        // 如果表和控制器名称相同。
        $model = $this->model ?  $this->model : request()->controller();
        // 查询记录是否存在
        try {
            $count = model($model)->get(['id' => $id]);
        } catch (Exception $e) {
            return $this->result('', 0, $e->getMessage() );
        }
        if($count != 1) {
            return $this->result('', 0, '你要删除的记录不存在');
        }
        try {
            $res = model($model)->save(['status' => config('code.status_delete')], ['id' => intval($id)]);
        } catch (Exception $e) {
            return $this->result('', 0, $e->getMessage() );
        }
        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'OK' );
        } else {
            return $this->result('', 0, '删除失败');
        }
    }

    /**
     * 通用修改状态方法
     */
    public function status() {
        $data = input('param.');
        // tp5 validate
        // 如果表和控制器名称相同。
        $model = $this->model ?  $this->model : request()->controller();
        // 查询记录是否存在
//        try {
//            $count = model($model)->get(['id' => $data['id']]);
//        } catch (Exception $e) {
//            return $this->result('', 0, $e->getMessage() );
//        }
//        if($count != 1) {
//            return $this->result('', 0, '你要删除的记录不存在');
//        }

        try {
            $res = model($model)->save(['status' => $data['status']], ['id' => intval($data['id'])]);
        } catch (Exception $e) {
            return $this->result('', 0, $e->getMessage() );
        }

        if($res) {
            return $this->result(['jump_url' => $_SERVER['HTTP_REFERER']], 1, 'Ok' );
        }
        return $this->result('', 0, '修改失败');

    }



}
