<?php
/**
 * Identify.php
 * Created By Colorful
 * Date:2018/2/26
 * Time:下午9:58
 */
namespace app\common\validate;
use think\Validate;

class Identify extends Validate {

    protected $rule = [
        'id' => ['require|number|length:11'],
    ];
}
