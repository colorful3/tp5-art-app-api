<?php
/**
 * Upvote.php
 * Created By Colorful
 * Date:2018/3/3
 * Time:下午11:59
 */
namespace app\api\controller\v1;
use app\common\model\News;

class Upvote extends AuthBase {

    /**
     * 点赞功能接口
     * @return array
     */
    public function save() {
        $id = input('post.id', 0 , 'intval');
        if( !$id ) {
            return show(config('code.error'), 'id为必须传参数', [], 404);
        }
        // 判断news
        $news = News::get(['id' => $id]);
        if( !$news || $news->status != 1 ) {
            return show(config('code.error'), '新闻不存在或新闻状态不合法', [], 403);
        }

        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id,
        ];
        // 查询数据库中是否存在点赞
        $user_news = model('UserNews')->get($data);
        if($user_news) {
            return show(config('code.error'), '您已经点赞过了', [], 401);
        }
        try {
            model('News')->where(['id' => $id])->setInc('upvote_count');
            $user_news_id = model('UserNews')->add($data);
            if($user_news_id) {
                return show( config('code.success'), 'OK', [], 202 );
            } else {
                return show( config('code.error'), '点赞失败', [], 500 );
            }
        } catch (\Exception $e) {
            return show( config('code.error'), $e->getMessage(), [], 500 );
        }
    }

    /**
     * 取消点赞接口
     */
    public function delete() {
        $id = input('delete.id', 0, 'intval');
        if( !$id ) {
            return show(config('code.error'), 'id为必须传参数', [], 404);
        }
        // 判断news
        $news = News::get(['id' => $id]);
        if( !$news || $news->status != 1 ) {
            return show(config('code.error'), '新闻不存在或新闻状态不合法', [], 403);
        }

        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id,
        ];
        // 查询数据库中是否存在点赞
        $user_news = model('UserNews')->get($data);
        if(!$user_news) {
            return show(config('code.error'), '没有被点赞过，无法取消', [], 401);
        }
        try {
            model('News')->where(['id' => $id])->setDec('upvote_count');
            $user_news_id = model('UserNews')->where($data)->delete();
            if( $user_news_id ) {
                return show( config('code.success'), 'OK' );
            } else {
                return show( config('code.error'), '取消点赞失败', [], 500 );
            }
        } catch (\Exception $e) {
            return show( config('code.error'), $e->getMessage(), [], 500 );
        }
    }

    /**
     * 查看文章是否被该用户点赞
     */
    public function read() {
        $id = input('param.id', 0, 'intval');
        if( empty($id) ) {
            return show(config('code.error'), 'id为必须传参数', [], 404);
        }
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id
        ];
        $user_news = model('UserNews')->get($data);
        if($user_news) {
            return show(config('code.success'), 'OK', ['isUpovte' => 1], 200);
        } else {
            return show(config('code.error'), 'OK', ['isUpovte' => 0], 200);
        }
    }
}
