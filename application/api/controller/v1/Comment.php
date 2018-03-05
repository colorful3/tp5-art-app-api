<?php
/**
 * Comment.php
 * Created By Colorful
 * Date:2018/3/4
 * Time:上午7:56
 */
namespace app\api\controller\v1;

class Comment extends AuthBase {

    /**
     * 评论、回复接口
     */
    public function save() {
        // todo 过滤
        $data = input('post.', []);
        // todo 校验 news_id, content, to_user_id, parent_id

        // 查询news是否存在，状态是否正常
        $news = \app\common\model\News::get(['id' => $data['news_id']]);
        if(!$news || $news->status != 1 ) {
            return show( config('code.error'), '新闻不存在或新闻状态不合法', [] , 403 );
        }

        $data['user_id'] = $this->user->id;
        try {
            $comment_id = model('Comment')->add($data);
            model('news')->where(['id' => $data['news_id']])->setInc('comment_count');
            if( $comment_id ) {
                return show( config('code.success'), 'OK', [], 202 );
            } else {
                return show( config('code.error'), '评论失败', [] , 500 );
            }
        } catch (\Exception $e) {
            return show( config('code.error'), $e->getMessage(), [] , 500 );
        }
    }

    /**
     * 获取评论列表接口
     */
    public function read() {
        // 获取文章id
        $news_id = input('param.id', 0, 'intval');
        if(!$news_id) {
            return show(config('code.error'), 'id为必须传参数', [], 404);
        }
        try {
            $count = model('Comment')->getNormalCommentsCountByCondition(['news_id' => $news_id]);
            $this->getPageParams( input('param.') );
            $comments = model('Comment')->getNormalCommentsByCondition(['news_id' => $news_id], $this->offset, $this->page_size);
        } catch (\Exception $e) {
            return show(config('code.error'), $e->getMessage(), [], 500);
        }

        $result = [
            'total' => $count,
            'page_num' => ceil($count / $this->page_size),
            'list' => $comments
        ];
        return show( config('code.success'), 'OK', $result );
    }
}