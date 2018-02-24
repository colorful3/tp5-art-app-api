<?php
/**
 * Common.php
 * Created By Colorful
 * Date:2018/2/22
 * Time:下午7:10
 */
namespace app\api\controller;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\OpenSSLAES;
use app\common\lib\Time;
use function Sodium\crypto_aead_aes256gcm_decrypt;
use think\Cache;
use think\Controller;
use app\common\lib\Aes;

class Common extends Controller {

    // header头数据
    public $headers = '';

    // 当前页
    public $page = 1;

    // 每页显示条数
    public $page_size = 10;
    // 分页的偏移量
    public $offset = 0;

    /**
     * 初始化方法
     */
    public function _initialize() {
        // $this->testAes();
        $this->checkRequestAuth();
    }

    /**
     * 检测每次app请求的数据是否合法
     */
    public function checkRequestAuth() {
        // 首先、需要获取headers
        $headers = request()->header();

        // sign算法

        // 基础参数校验
        if( empty($headers['sign']) ) {
            throw new ApiException('sign参数未传递', 400);
        }
        $app_type = $headers['app_type'];
        if( !in_array($app_type, config('app.app_types')) ) {
            throw new ApiException('app_type不合法', 400);
        }
        // 其他自行添加

        // 验证sign
        if( !IAuth::checkSignPass($headers) ) {
            throw new ApiException('授权码sign验签失败', 400);
        }
        // sign唯一性  1、文件缓存 2、mysql 3、redis
        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));

        $this->headers = $headers;
    }


    // ase加密解密测试
    public function testAes() {
        $data = [
            'did' => 'ColorfulC',
            'version' => '1.0',
            'time' => Time::getTimeStamp(), // 客户端的13位时间戳，为了降低重复性，所以
        ];
        $str = 'i3yvWajKiytzc2WF+oCPn1agkJWc2LG0/k3yybYdW1vmzhakfsSsptomufDqzj0J';
        // echo IAuth::setSign($data);exit;
        echo (new OpenSSLAES(config('app.aeskey')))->decrypt($str);exit;
    }

    /**
     * @param array $news
     * @return array
     */
    protected function getDealNews($news=[]) {
        if(empty($news)) {
            return [];
        }
        $cats = config('cat.lists');
        foreach($news as $key=>$new) {
            $news[$key]['catname'] = $cats[$new['catid']] ?
                $cats[$new['catid']] : '-';
        }
        return $news;
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