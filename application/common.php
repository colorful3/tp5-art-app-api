<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function pagination($obj) {
    if(!$obj) {
        return '';
    }
    $params = request()->param();
    // return '<div class="imooc-app">' . $obj->appends($params)->render() . '</div>' ;
}

// 获取栏目名称
function getCatName($catId) {
    if(!$catId) {
        return '';
    }
    $cats = config('cat.lists');
    return !empty($cats[$catId]) ? $cats[$catId] : '';
}

function yesOrNo($str) {
    return $str ? '<span style="color: red;">是</span>' : '<span>否</span>';
}

// 显示状态方法
function status( $id, $status ) {
//    if(!intval($id) || !in_array($status, config('code'))) {
//        return '';
//    }
    $controller = request()->controller();
    $sta = $status == 1 ? 0 : 1;
    $url = url($controller.'/status', ['id' => $id, 'status' => $sta, ]);
    if($status == 1) {
        $str = "<a href='javascript:;' onclick='capp_status(this)' 
            title='修改状态' status_url='".$url."'><span class='label label-success radius'>正常</span></a>";
    } elseif ($status == 0) {
        $str = "<a href='javascript:;' onclick='capp_status(this)' 
            title='修改状态' status_url='".$url."'><span class='label label-danger radius'>待审</span></a>";
    }
    return $str;
}

/**
 * 通用api接口数据输出
 * @param int $status 业务状态码
 * @param string $message 提示消息
 * @param array $data 接口数据
 * @param int $code http状态码
 * @return array
 */
function show( $status, $message, $data = [], $http_code=200 ) {
    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];

    return json( $data, $http_code );
}