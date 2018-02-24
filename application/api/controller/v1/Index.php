<?php
/**
 * Index.php
 * Created By Colorful
 * Date:2018/2/24
 * Time:下午8:03
 */
namespace app\api\controller\v1;

use app\api\controller\Common;

class Index extends Common {

    /**
     * 首页接口
     * 1、头图  4条
     * 2、推荐位列表 默认20条
     * @url http://app.colorful.com/api/v1/index
     */
    public function index() {
        // 获取头图
        $heads = model('News')->getIndexHeadNormalNews(4);

        $positions = model('News')->getPositionNormalNews();
        $positions = $this->getDealNews($positions);

        $result = [
            'heads' => $heads,
            'positions' => $positions
        ];
        return show(config('code.success'), 'ok', $result, 200);

    }

}