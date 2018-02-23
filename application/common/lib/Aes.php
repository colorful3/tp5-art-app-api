<?php
/**
 * Aes.php
 * Created By Colorful
 * Date:2018/2/23
 * Time:上午8:59
 */
namespace app\common\lib;


class Aes {

    private $key = null;


    /**
     * Aes constructor.
     */
    public function __construct() {
        // 需要小伙伴在配置文件app.php中定义aeskey
        $this->key = config('app.aeskey');
    }


    /**
     * aes 加密
     * @param string $input
     * @return string
     */
    public function encrypt($input = '') {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $iv);

        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);

        return $data;

    }
    /**
     * 填充方式 pkcs5
     * @param String $text 		 原始字符串
     * @param String $blocksize   加密长度
     * @return String
     */
    private function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * aes 解密
     * @param string $input 解密的字符串
     * @param string $key   解密的key
     * @return String
     */
    public function decrypt($sStr) {
        $decrypted= mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$this->key,base64_decode($sStr), MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }

}
