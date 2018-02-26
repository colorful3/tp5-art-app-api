<?php
/**
 * Version.php
 * Created By Colorful
 * Date:2018/2/26
 * Time:上午9:32
 */
namespace app\common\model;

class Version extends Base {


    /**
     * 通过app_type获取最后一条数据
     * @param string $app_type
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getLastNormalVersionBaAppType($app_type='') {
        $where = [
            'status' => 1,
            'app_type' => $app_type,
        ];
        $order = [
            'id' => 'desc'
        ];
        $result = $this->where($where)
            ->order($order)
            ->limit(1)
            ->find();
        return $result;
    }
}