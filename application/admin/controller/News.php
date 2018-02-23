<?php
/**
 * news.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午4:12
 */
namespace app\admin\controller;

use think\Exception;

class News extends Base {

    public function index() {
        // 获取数据，把数据填充到模板
        // $news = model('News')->getNews();
        $data = input('param.');
        $query = http_build_query($data);

        $where = [];

        // 转换查询条件
        if( !empty($data['start_time']) && !empty($data['end_time'])
            && $data['start_time'] < $data['end_time']
        ) {
            $where['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }
        if( !empty($data['catid']) ) {
            $where['catid'] = intval($data['catid']);
        }
        if( !empty($data['title']) ) {
            $where['title'] = [
                'like', '%'. trim( $data['title'] ).'%'
            ];
        }
        $this->getPageParams($data);

        // 根据条件获取数据
        $news = model('News')->getNewsByCondition($where, $this->offset, $this->page_size);
        // 获取满足条件的数据总数
        $total = model('News')->getNewsCountByCondition($where);
        // 求出总页数
        $total_page = ceil( $total / $this->page_size );

        return $this->fetch('', [
            'cats' => config('cat.lists'),
            'news' => $news,
            'total' => $total,
            'total_page' => $total_page,
            'curr' => $this->page,
            'start_time' => empty($data['start_time']) ? "" :$data['start_time'],
            'end_time' => empty($data['end_time']) ? "" :$data['end_time'],
            'catid' => empty($data['catid']) ? "" :$data['catid'],
            'title' => empty($data['title']) ? "" :$data['title'],
            'query' => empty($query) ? "" : $query,
        ]);
    }

    // 添加文章
    public function add() {
        if( request()->isPost() ) {
            $data = input('post.');
            // $validate = validate('news');
            $id = '';
            try {
                $id = model('news')->add($data);
            } catch (Exception $e) {
                return $this->result('', 0, '新增失败，数据库报错');
            }
            if($id) {
                return $this->result(['jump_url' => url('news/index')],1, 'OK' );
            } else {
                return $this->result('', 0, '新增失败');
            }

        } else {
            return $this->fetch('', [
                'cats' => config('cat.lists')
            ]);
        }
    }

    public function edit() {
        if( request()->isPost() ) {

        } else {
            $id = input('get.id');
            $news_info = model('News')->get(['id' => $id]);
            if( !$news_info ) {

            }
        }
    }




}
