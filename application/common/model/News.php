<?php
/**
 * News.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午8:09
 */
namespace app\common\model;

class News extends Base {

    /**
     * 分页得到数据
     * @param array $data
     * @return array
     */
    public function getNews($data = []) {
        $data['status'] = [
            'neq', config('code.status_delete')
        ];
        $order = [
            'id' => 'desc'
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate(1);
        if($result) {
            return $result;
        } else {
            return [];
        }
    }

    /**
     * 根据条件获取列表的数据
     * @param array $condition
     * @param int $offset
     * @param int $page_size
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNewsByCondition($condition = [], $offset, $page_size = 1) {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }
        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->field($this->_getListField())
            ->limit($offset, $page_size)
            ->order($order)
            ->select();
        // echo $this->getLastSql();
        return $result;
    }


    /**
     * 根据条件获取列表的数据数量
     * @param array $condition
     * @return int|string
     */
    public function getNewsCountByCondition($condition=[]) {
        if(!isset($condition['status'])) {
            $condition['status'] = [
                'neq', config('code.status_delete')
            ];
        }

        $count = $this->where($condition)
            ->count();
        return $count;
    }

    /**
     * 获取首页头图数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getIndexHeadNormalNews($num = 4) {
        $data = [
            'status' => 1,
            'is_head_figure' => 1,
        ];

        $order = [
            'id' => 'desc'
        ];
        $news = $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
        if( !$news ) {
            return [];
        } else {
            return $news;
        }
    }

    /**
     * 获取首页推荐数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getPositionNormalNews($num=20) {
        $data = [
            'status' => 1,
            'is_position' => 1,
        ];
        $order = [
            'id' => 'desc',
        ];
        $news = $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
        return $news;
    }

    /**
     * 获取排行数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRankNormaNews($num=5) {
        $data = [
            'status' => 1,
        ];
        $order = [
            'read_count' => 'desc',
        ];
        $rank_news = $this->where($data)
            ->field($this->_getListField())
            ->order($order)
            ->limit($num)
            ->select();
        return $rank_news;
    }

    /**
     * 获取参数数据字段
     * @return array
     */
    private function _getListField() {
        return ['id', 'catid', 'image', 'title', 'read_count'
        ,'status', 'is_position', 'update_time', 'create_time'];
    }

}
