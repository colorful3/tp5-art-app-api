<?php
/**
 * Index.php
 * Created By Colorful
 * Date:2018/2/24
 * Time:下午8:03
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use think\Log;

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

    /**
     * 客户端初始化接口
     * 1、检测app是否需要升级
     * 2、
     */
    public function init() {
        try {
            // 根据app_type 去ent_version中查询最新的一条数据，用表中的version和header中verison对比
            $version = model('Version')->getLastNormalVersionBaAppType($this->headers['app_type']);
        } catch (\Exception $e) {
            return show(0, $e->getMessage());
        }
        if(empty($version)) {
            return new ApiException('error, version not found', 404);
        }
        if($version->version > $this->headers['version']) {
            $version->is_update = $version->is_force == 1 ? 2 : 1; // 1、需要更新，不强制，2、强制更新
        } else {
            $version->is_update = 0; // 不需要更新
        }

        // 记录用户的基本信息，用于统计
        $active = [
            'version' => $this->headers['version'],
            'app_type' => $this->headers['app_type'],
            'did' => $this->headers['did'],
        ];
        try {
            model('AppActive')->add($active);
        } catch (\Exception $e) {
            // todo 不需要暴露信息给客户端。
            // Log::write(); 可以记录错误日志
        }
        return show(config('code.success'), 'OK', $version, 200);

    }

}