<?php
/**
 * Rank.php
 * Created By Colorful
 * Date:2018/2/24
 * Time:下午9:20
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;

class Rank extends Common {

    /**
     * 获取排行榜数据
     * 1、获取数据库，按照read_count排序 5 -10
     * 2、redis
     */
    public function index() {
        try{
            $ranks = model('news')->getRankNormaNews();
            $ranks = $this->getDealNews($ranks);
        } catch (\Exception $e) {
            return new ApiException('error', 400, 0);
        }
        return show(config('code.success'), 'ok', $ranks, 200);
    }
}
