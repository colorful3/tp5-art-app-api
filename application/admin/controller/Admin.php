<?php
namespace app\admin\controller;

use think\Controller;
use think\Exception;
use app\common\lib\IAuth;

class Admin extends Controller
{
    public function add() {
        // 判定是否是post提交
        if(request()->isPost()) {
            // 打印提交数据
            $data = input('post.');
            // halt($data);
            $validate = validate('AdminUser');
            if ( !$validate->check($data) ) {
                $this->error($validate->getError());
            }
            $data['password'] = IAuth::setPassword($data['password']);
            $data['status'] = 1;
            // 数据入库
            $id = '';
            try {
                $id = model('AdminUser')->add($data);
            } catch(Exception $e) {
                $this->error($e->getMessage());
            }
            if($id) {
                $this->success('id=' . $id . '的用户新增成功');
            } else {
                $this->error('error');
            }
        } else {
            return $this->fetch();
        }
    }


}
