<?php
/**
 * Identify.php
 * Created By Colorful
 * Date:2018/2/26
 * Time:下午9:47
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Alidayu;

class Identify extends Common {

    /**
     * 设置短信验证码
     */
    public function save() {
        if(!request()->isPost()) {
            return show(config('code.error'), '来路不明', 403);
        }
        // 检验数据 Validate
        // $validate = validate('Identify');
        if( !is_mobile(input('post.id')) || empty(input('post.id')) ) {
            return show(config('code.error'), '请输入正确的手机号', [], 403);
        }

        // 发送短信验证码
        if ( Alidayu::getInstance()->setSmsIdentify(input('post.id')) ) {
            return show( config('code.success'), 'OK', [], 201 );
        } else {
            return show( config('code.error'), 'error', [], 403 );
        }

    }


}
