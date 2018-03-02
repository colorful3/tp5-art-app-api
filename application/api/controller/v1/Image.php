<?php
/**
 * Image.php
 * Created By Colorful
 * Date:2018/3/2
 * Time:下午4:32
 */
namespace app\api\controller\v1;


use app\common\lib\Upload;

/**
 * 必须继承AuthBase，只有登录的用户才可以修改头像
 * Class Image
 * @package app\api\controller\v1
 */
class Image extends AuthBase {

    /**
     * 双穿图片接口
     * @return array
     */
    public function save() {
        // print_r($_FILES);
        $image = Upload::image();
        if( $image ) {
            return show( config('code.success'),'OK', config('qiniu.image_url') . '/' . $image );
        } else {
            return show( config('code.error'),'upload error', [], 500 );
        }
    }


}