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

// 短信验证码相关
Route::resource('api/:ver/identify', 'api/:ver.identify');

// 登录路由
Route::post('api/:ver/login', 'api/:ver.login/save');
Route::post('api/:ver/logout', 'api/:ver.login/logout');

// 个人中心路由
Route::resource('api/:ver/user', 'api/:ver.user');

// 验证用户昵称唯一性路由
Route::post('api/:ver/user/checkUsername', 'api/:ver.user/checkUsername');

// 图片上传路由
Route::post('api/:ver/image', 'api/:ver.image/save');

// 点赞路由
Route::post('api/:ver/upvote', 'api/:ver.upvote/save');
// 取消点赞
Route::delete('api/:ver/upvote', 'api/:ver.upvote/delete');
// 获取是否点赞
Route::get('api/:ver/upvote/:id', 'api/:ver.upvote/read');

// 评论
Route::post('api/:ver/comment', 'api/:ver.comment/save');

Route::get('api/:ver/comment/:id', 'api/:ver.comment/read');