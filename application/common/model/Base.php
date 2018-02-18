<?php
/**
 * Base.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午8:07
 */
namespace app\common\model;
use think\Model;

class Base extends Model {
    protected $autoWriteTimestamp = true;

    public function add($data)
    {
        if(!is_array($data)) {
            exception('传递数据不合法');
        }
        $ret = $this->allowField(true)->save($data);
        if($ret) {
            return $this->id;
        } else {
            exception('数据入库失败');
        }
    }
}
