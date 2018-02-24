<?php
/**
 * Cat.php
 * Created By Colorful
 * Date:2018/2/23
 * Time:下午9:33
 */
namespace app\api\controller\v1;

use app\api\controller\Common;

class Cat extends Common {

    /**
     * 栏目接口
     * @url http://app.colorful.com/api/cat/read
     */
    public function read() {
        $cats = config('cat.lists');
        $result = [];
        $result[] = [
            'catid' => 0,
            'catname' => '首页'
        ];

        foreach( $cats as $cat_id => $cat_name ) {
            $result[] = [
                'catid' => $cat_id,
                'catname' => $cat_name
            ];
        }
        return show(config('app.success'), 'ok', $result, 200);
    }


}
