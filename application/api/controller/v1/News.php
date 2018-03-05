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

    /**
     * 新闻详情接口
     */
    public function read() {
        // 详情页面两种方式： 1、h5页面 2、接口的形式

        $id = input('param.id', 0, 'intval');
        if( empty($id) ) {
            return new ApiException('you need to transfer id', 404);
        }

        // 通过id 获取数据表里的数据
        try {
            $news = model('News')->get($id);
        } catch (\Exception $e) {
            return show(0, $e->getMessage() );
        }

        if( empty($news) || $news->status != config('code.status_normal') ) {
            return new ApiException('不存在该新闻', 404);
        }

        try {
            // 增加阅读数
            model('News')->where(['id' => $id])->setInc('read_count');
        } catch (\Exception $e) {
            return show(0, $e->getMessage() );
        }
        $cats = config('cat.lists');
        $news->catname = $cats[$news->id];
        // 返回详情数据
        return show( config('code.success'), 'ok', $news, 200 );

    }


}
