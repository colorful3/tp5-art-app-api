<?php
/**
 * Alidayu.php  阿里大于发送短信基础类库（使用单利模式）
 * Created By Colorful
 * Date:2018/2/26
 * Time:下午1:38
 */
namespace app\common\lib;

require_once '/Applications/MAMP/htdocs/study/app.colorful.com/extend/ali/api_sdk/vendor/autoload.php';
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use think\Cache;
use think\Log;

Config::load();


class Alidayu {

    // 定义日志表示
    const LOG_TPL = 'alidayu:';

    // 1、私有静态变量保存全局实例
    private static $_instance = null;

    public static $acsClient = null;

    // 2、单例模式，私有构造函数
    private function __construct() {

    }

    /**
     * 静态方法，单例模式的入口
     */
    public static function getInstance() {
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = config('aliyun.appKey'); // AccessKeyId

        $accessKeySecret = config('aliyun.secretKey'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 生成短信验证码
     * @param int $phone
     * @return bool
     */
    public function setSmsIdentify($phone = 0) {
        // Log::write(self::LOG_TPL . 'setsms---start');
        // 判断是否是手机号
        if( !is_mobile($phone) ) {
            return false;
        }
        // 生成随机验证码
        $code = rand(1000, 9999);
        // 记录日志
        Log::write('');
        try {
            // 初始化SendSmsRequest实例用于设置发送短信的参数
            $request = new SendSmsRequest();

            // 必填，设置短信接收号码
            $request->setPhoneNumbers($phone);

            // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
            $request->setSignName(config('aliyun.signName'));

            // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
            $request->setTemplateCode(config('aliyun.templateCode'));

            // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
            $request->setTemplateParam(json_encode(array(  // 短信模板中字段的值
                "code" => $code,
                // "product" => "dsd"
            ), JSON_UNESCAPED_UNICODE));

            // 可选，设置流水号
            //$request->setOutId("yourOutId");

            // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
            $request->setSmsUpExtendCode("1234567");

        } catch (\Exception $e) {
            // 记录日志
            Log::write(self::LOG_TPL . 'set----' . $e->getMessage());
            return false;
        }

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        if($acsResponse->Message === 'OK') {
            // 把验证码保存到文件缓存中
            Cache::set($phone, $code, config('aliyun.identify_time'));
            return true;
        } else {
            // 记录日志
            Log::write( self::LOG_TPL . 'set----' . json_encode($acsResponse) );
        }
        return false;
    }

    /**
     * 根据手机号获得验证码
     * @param $phone
     * @return bool|mixed
     */
    public function checkSmsIdentify($phone=0) {
        if(!$phone) {
            return false;
        }
        return Cache::get($phone);
    }

}
