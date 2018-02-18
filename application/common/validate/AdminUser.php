<?php
/**
 * AdminUser.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午1:12
 */
namespace app\common\validate;
use think\Validate;

class AdminUser extends Validate {

    protected $rule = [
        'username' => 'require|max:20',
        'password' => 'require|max:20',
    ];
}
