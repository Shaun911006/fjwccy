<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/21
 * Time:下午1:18
 * Description:
 */

namespace fjwccy\content;

use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class Flow extends Content
{
    protected array $items = [
        //必填，本资金流水单号。
        "documentNumber"        => '',
        //必填，本资金流水单上传到省级监测系统的时间。
        "sendToProDateTime"     => '',
        //实际承运人名称
        "carrier"               => '',
        //实际承运人统一社会信用代码或证件号码
        "actualCarrierId"       => '',
        //车辆牌照号
        "vehicleNumber"         => '',
        //车牌颜色代码
        "vehiclePlateColorCode" => '',
        //运单列表
        "shippingNoteList"      => [],
        //财务列表
        "financiallist"         => [],
        //备注
        "remark"                => '',
    ];

    public function formatData()
    {
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        //判断有没有为空的必填参数
        $validate = new Validate();
        $validate->check($this->items, [
            'documentNumber'        => 'require|max:35',
            'sendToProDateTime'     => 'require|dateFormat:YmdHis',
            'carrier'               => 'require|max:512',
            'actualCarrierId'       => 'require|max:50',
            'vehicleNumber'         => 'require|max:35',
            'vehiclePlateColorCode' => 'require|max:2',
            'financiallist'         => 'require|array',
            'shippingNoteList'      => 'require|array',
            'remark'                => 'max:256',
        ]);
    }
}