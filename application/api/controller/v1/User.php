<?php
/**
 * User.php
 * Created By Colorful
 * Date:2018/3/2
 * Time:下午12:16
 */
namespace app\api\controller\v1;


class User extends AuthBase {

    public function save() {
        $user = $this->user;
        var_dump($user);
    }

}