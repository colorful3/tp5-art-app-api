<?php
/**
 * Comment.php
 * Created By Colorful
 * Date:2018/3/4
 * Time:上午8:03
 */
namespace app\common\model;

use think\Db;

class Comment extends Base {


    /**
     * 通过条件获取评论数量
     * @param array $param
     * @return int|string
     */
    public function getNormalCommentsCountByCondition( $param = [] ) {
        // todo AND status = 1
        $count = Db::table('cc_comment')
            ->alias(['cc_comment' => 'a', 'cc_user' => 'b'])
            ->join('cc_user', 'a.user_id = b.id AND a.news_id = '. $param['news_id'])
            ->count();
        return $count;
    }

    /**
     * 通过条件获取评论列表
     * @param array $param
     * @param $offset
     * @param int $page_size
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getNormalCommentsByCondition( $param = [], $offset, $page_size = 1 ) {

        $result = Db::table('cc_comment')
            ->alias(['cc_comment' => 'a', 'cc_user' => 'b'])
            ->join('cc_user', 'a.user_id = b.id AND a.news_id = ' . $param['news_id'] )
            ->limit($offset, $page_size)
            ->order(['a.id' => 'desc'])
            ->select();

        return $result;
    }


}