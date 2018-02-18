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
        $condition['status'] = [
            'neq', config('code.status_delete')
        ];
        $order = ['id' => 'desc'];

        $result = $this->where($condition)
            ->limit($offset, $page_size)
            ->order($order)
            ->select();
        // echo $this->getLastSql();
        return $result;
    }


    /**
     * 根据条件获取列表的数据数量
     * @param array $param
     * @return int|string
     */
    public function getNewsCountByCondition($param = []) {
        $condition['status'] = [
            'neq', config('code.status_delete')
        ];

        $count = $this->where($condition)
            ->count();
        return $count;
    }

}
