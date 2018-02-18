<?php
/**
 * Image.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午4:30
 * @desc 图片上传
 */
namespace app\admin\controller;

use think\Exception;
use think\Request;
use app\common\lib\Upload;

class Image extends Base {

    public function localUpload() {
        $file = Request::instance()->file('file');
        $info = $file->move('upload');
        if( $info->getPathname() ) {
            echo json_encode([
               'status' => 1,
                'message' => 'Ok',
                'data' => '/' . $info->getPathname()
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => 'error',
                'data' => []
            ]);
        }
    }

    public function upload() {
        try {
            $image_key = Upload::image();
        } catch (Exception $e) {
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
        if($image_key) {
            echo json_encode([
                'status' => 1,
                'message' => 'Ok',
                'data' => config('qiniu.image_url') . '/' . $image_key
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => 'error',
                'data' => []
            ]);
        }
    }
}