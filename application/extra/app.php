<?php
/**
 * app.php
 * Created By Colorful
 * Date:2018/2/18
 * Time:下午2:57
 */

return [
    'password_salt' => '!@#$%^&*',  // 密码加密盐
    'aeskey' => 'ccapp!@#$%^&*()',  // ase加密盐，服务端和客户端必须保持一致
    'app_types' => [
        'ios',
        'android',
    ],
    'app_sign_time' => 10,   // sign有效时间
    'app_sign_cache_time' => 20,   // sign有效时间
    'success' => 1,
    'error' => 0,
];
