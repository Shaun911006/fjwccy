<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/21
 * Time:下午1:14
 * Description:
 */

namespace fjwccy;

use fjwccy\exception\WccyException;
use fjwccy\exception\WccyTokenException;

class WccyClient
{
    const YD = 'WLHY_YD1001';
    const ZJ = 'WLHY_ZJ1001';
    const CL = 'WLHY_CL1001';
    const JSY = 'WLHY_JSY1001';
    /**
     * @var int|mixed
     */

    private array $documentName = [
        self::YD  => '电子运单上传',
        self::ZJ  => '资金流水单上传',
        self::CL  => '车辆基本信息上传',
        self::JSY => '驾驶员基本信息上传',
    ];

    private string $userName = '';
    private string $authUrl = '';
    private string $sendUrl = '';
    private string $smPublicKey = '';
    private string $passWord = '';
    private string $logFPath;

    /**
     * @param $config
     */
    public function __construct($config)
    {
        $this->userName    = $config['userName'] ?? '';
        $this->authUrl     = $config['authUrl'] ?? '';
        $this->sendUrl     = $config['sendUrl'] ?? '';
        $this->smPublicKey = $config['smPublicKey'] ?? '';
        $this->passWord    = $config['passWord'] ?? '';
        $this->logFPath    = $config['logFPath'] ?? '';
    }

    public static function __make($config): WccyClient
    {
        return new static($config);
    }

    /**
     * @throws
     */
    public function getToken()
    {
        $res = $this->post_json($this->authUrl, json_encode([
            'userName' => $this->userName,
            'passWord' => $this->passWord,
        ]));
        if (($res['code'] ?? 9999) === 1001) {
            return $res['data'];
        } else {
            throw new WccyException('token获取失败');
        }
    }

    /**
     * @param string $token 令牌
     * @param string $ipcType WLHY_YD1001/WLHY_ZJ1001/WLHY_CL1001/WLHY_JSY1001
     * @param array $data 报文数据
     * @return mixed
     * @throws WccyException
     * @throws WccyTokenException
     */
    public function send(string $token, string $ipcType, array $data)
    {
        //加密报文
        $encryptedData = SmTool::encode($this->smPublicKey, json_encode($data));

        $dataBody = [
            'root' => [
                'header' => [
                    'messageReferenceNumber' => $this->getGuid(),
                    'documentName'           => $this->documentName[$ipcType],
                    'documentVersionNumber'  => '2.0',
                    'senderCode'             => '350000',
                    'enterpriseSenderCode'   => $this->userName,
                    'messageSendingDateTime' => date('YmdHis'),
                    'ipcType'                => $ipcType,
                    'token'                  => $token,
                ],
                'body'   => $encryptedData
            ],
        ];

        $num = rand(1000, 9999);
        $this->log($num, $dataBody, 1);
        $res = $this->post_json($this->sendUrl, json_encode($dataBody));
        $this->log($num, $res, 2);
        return $this->parseResult($res);
    }

    /**
     * 解析返回结果
     * @throws WccyTokenException
     * @throws WccyException
     * 序号    代码    描述    处理方式
     * 1    1001    成功
     * 2    2001    token认证失败    用户错误或token失效，请确认用户并重新获取token
     * 3    3001    报文参考号重复    重新生成报文参考号
     * 4    3002    ipctype错误    错误的业务接口类型
     * 5    3003    发送消息队列失败    请联系管理员
     * 6    3004    交换报文解析错误    请确认报文格式是否正确
     * 7    3005    字段校验失败    请查看返回的具体错误信息
     * 8    3006    对称密钥解密失败    请确认公钥是否失效
     * 9    3007    业务报文解密错误    请确对称密钥是否正确
     * 10    3008    业务报文解析错误    请确认业务报文格式是否正确
     * 11    9001    服务器内部错误    联系管理员
     */
    private function parseResult($res)
    {
        try {
            switch ($res['code']) {
                case 1001:
                    return is_null($res['msg']) ? '' : $res['msg'];
                case 2001:
                    throw new WccyTokenException($res['msg']);
                default:
                    throw new WccyException($res['msg']);
            }
        } catch (WccyTokenException|WccyException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new WccyException($e->getMessage());
        }
    }

    private function getGuid(): string
    {
        mt_srand((double)microtime() * 10000);//optional for php 4.2.0 and up.
        $chart = md5(uniqid(rand(), true));
        return substr($chart, 0, 8)
            . substr($chart, 8, 4)
            . substr($chart, 12, 4)
            . substr($chart, 16, 4)
            . substr($chart, 20, 12);
    }

    /**
     * @param string $url
     * @param string $data
     * @return array
     * @throws WccyException
     */
    public function post_json(string $url, string $data): array
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length:' . strlen($data),
            'Cache-Control: no-cache',
            'Pragma: no-cache'
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new WccyException('CURL请求失败:' . curl_error($curl));
        }
        curl_close($curl);
        return json_decode($res, true);
    }

    /**
     * 记录日志
     * @param string $num 请求的编号
     * @param mixed $content 记录内容
     * @param int $type 内容类型 1.请求 2.响应
     * @return void
     */
    public function log(string $num = '1000', $content = '', int $type = 1)
    {
        try {
            if (empty($this->logFPath)){
                return;
            }
            $dir1 = $this->logFPath . 'wccy_log';
            if (!is_dir($dir1)) {
                mkdir($dir1, 0777, true);
            }
            $dir2 = $dir1 . DIRECTORY_SEPARATOR . date('Y-m');
            if (!is_dir($dir2)) {
                mkdir($dir2, 0777, true);
            }
            $file = $dir2 . DIRECTORY_SEPARATOR . date('d') . '.log';

            $logStr = '[' . date('H:i:s') . ']  (' . $num . ')  ' . ($type === 1 ? 'Request' : ($type === 2 ? 'Response' : '')) . '   >>>>>>' . PHP_EOL .
                var_export($content, true) . PHP_EOL;
            file_put_contents($file, $logStr . PHP_EOL, FILE_APPEND);
        } catch (\Exception $e) {

        }
    }
}