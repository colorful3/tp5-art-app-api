<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get('test', 'api/test/index');
Route::put('test/:id', 'api/test/update'); // 修改
Route::delete('test/:id', 'api/test/delete'); // 删除
Route::post('test', 'api/test/save');

Route::get('api/:ver/cat', 'api/:ver.cat/read');
// 首页接口路由
Route::get('api/:ver/index', 'api/:ver.index/index');
// 初始化接口
Route::get('api/:ver/init', 'api/:ver.index/init');

// 新闻列表页
Route::resource('api/:ver/news', 'api/:ver.news');

// 新闻排行
Route::get('api/:ver/rank', 'api/:ver.rank/index');