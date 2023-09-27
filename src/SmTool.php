<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/21
 * Time:下午3:05
 * Description:
 */

namespace fjwccy;

use fjwccy\exception\WccyException;
use Rtgm\sm\RtSm2;
use Rtgm\sm\RtSm4;

class SmTool
{
    /**
     * @param $publicKey
     * @param $data
     * @return array
     * @throws WccyException
     */
    public static function encode($publicKey, $data): array
    {
        try {
            //生成16位随机字符串
            $code = self::randomString();

            $sm2 = new RtSm2();
            $sm4 = new RtSm4($code);
            //使用公钥对生成的字符串进行sm2加密
            $encryptedCode = '04' . $sm2->doEncrypt($code, $publicKey, C1C2C3);
            //使用code对业务报文进行sm4加密
            $encryptedContent = $sm4->encrypt($data, 'sm4-ecb', '', 'base64');
            return [
                'encryptedCode' => $encryptedCode,
                'encryptedContent' => $encryptedContent
            ];
        }catch (\Exception $e){
            throw new WccyException('参数加密失败');
        }

    }

    /**
     * 随机生成字符串
     * @param int $length
     * @return string
     */
    public static function randomString(int $length = 16): string
    {
        //字符组合
        $str     = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len     = strlen($str) - 1;
        $randStr = '';
        for ($i = 0; $i < $length; $i++) {
            $num     = mt_rand(0, $len);
            $randStr .= $str[$num];
        }
        return $randStr;
    }
}