<?php
/**
 * Login.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午2:44
 */
namespace app\common\validate;
use think\Validate;

class Login extends Validate {

    protected $rule = [
        'username' => 'require|min:5',
        'password' => 'require|min:8',
    ];
}
