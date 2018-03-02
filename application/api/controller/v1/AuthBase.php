<?php
/**
 * @name AuthBase.php
 * @desc 客户端auth登录权限基础类库
 * 1、每个接口（需要登录 个人中心、点赞、评论）都需要去继承它
 * 2、判定 access_user_token 是否合法
 * 3、用户信息 user
 * Created By Colorful
 * Date:2018/3/2
 * Time:下午12:09
 */
namespace app\api\controller\v1;
use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use app\common\lib\OpenSSLAES;
use app\common\model\User;


class AuthBase extends Common {

    /**
     * 登录用户的基本信息
     * @var array
     */
    public $user = [];

    /**
     * 初始化方法
     */
    public function _initialize() {
        parent::_initialize();
        if( !$this->isLogin() ) {
            throw new ApiException('你没有登录', 401);
        }
    }

    /**
     * 判断登录态
     * @return bool
     */
    public function isLogin() {
        if (empty($this->headers['access_user_token'])) {
            return false;
        }
        // 解密客户端传递的access_user_token
        $obj = new OpenSSLAES(config('app.aeskey'));
        // 从header中获取access_user_token并解密。todo 真实开发中需要客户端加密，服务端解密
        $access_user_token = $obj->decrypt($this->headers['access_user_token']);
        if (empty($access_user_token)) {
            return false;
        }
        if (!preg_match('/||/', $access_user_token)) {
            return false;
        }
        list($token, $id) = explode('||', $access_user_token);
        $user = User::get(['token' => $token, 'id' => $id]);
        if( !$user || $user->status != 1 ) {
            return false;
        }
        // 判断token时间是否过期
        if( time() > $user->time_out ) {
            return false;
        }
        // 把用户信息保存到成员变量中
        $this->user = $user;
        return true;
    }


}