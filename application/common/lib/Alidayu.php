<?php
/**
 * Alidayu.php  阿里大鱼发送短信基础类库（使用单利模式）
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


Config::load();


class Alidayu {

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
        // todo 判断phone

        // 生成验证码
        $code = rand(1000, 9999);
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
            // todo
        }

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        if($acsResponse->Message === 'OK') {
            return true;
        } else {

        }
        return false;
    }
}
