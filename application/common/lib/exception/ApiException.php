<?php
/**
 * ApiException.php
 * Created By Colorful
 * Date:2018/2/22
 * Time:下午5:09
 */
namespace app\common\lib\exception;
use think\Exception;

class ApiException extends Exception {


    public $message = '';
    public $http_code = 500;
    public $code = 0;


    public function __construct( $message='', $http_code = 0, $code = 0 ) {
        $this->message = $message;
        $this->http_code = $http_code;
        $this->code = $code;
    }



}
