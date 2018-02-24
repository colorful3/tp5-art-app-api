<?php
/**
 * News.php
 * Created By Colorful
 * Date:2018/2/24
 * Time:下午8:36
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;

class News extends Common {

    /**
     * 新闻列表
     */
    public function index() {
        // TODO 数据校验
        $data = input('get.');
        $where['status'] = config('code.status_normal');
        if(!empty($data['catid'])) {
            $where['catid'] = input('get.catid', 0, 'intval');
        }

        if(!empty($data['title'])) {
            $where['title'] = ['like', '%' . trim($data['title']) . '%'];
        }

        $this->getPageParams($data);
        try {
            $total = model('News')->getNewsCountByCondition($where);
            $news = model('News')->getNewsByCondition($where, $this->offset, $this->page_size);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), 400, 0);
        }

        $result = [
            'total' => $total,
            'page_num' => ceil($total / $this->page_size),
            'list' => $this->getDealNews($news),
        ];
        return show(1, 'ok', $result, 200);

    }


}
