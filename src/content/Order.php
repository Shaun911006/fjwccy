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

final class Order extends Content
{
    protected array $items = [
        //必填，上游企业委托运输单号。
        'originalDocumentNumber'        => '',
        //必填，运单号。
        'shippingNoteNumber'            => '',
        //运输总车辆数
        'vehicleAmount'                 => 1,
        //必填，分段运输和多车运输由四位数字组成，前两位代表一单多车的序号，后两位代表分段序号。若运输形式为一单一车填0000。
        'serialNumber'                  => '0000',
        //运输组织类型代码 1.公路运输
        'transportTypeCode'             => 1,
        //必填，网络货运经营者上传运单到省级监测系统的时间。YYYYMMDDhhmmss
        'sendToProDateTime'             => '',
        //网络货运经营者名称
        'carrier'                       => '',
        //统一社会信用代码
        'unifiedSocialCreditIdentifier' => '',
        //道路运输经营许可证编号
        'permitNumber'                  => '',
        //必填，网络货运经营者信息系统正式成交生成运单的日期时间。YYYYMMDDhhmmss
        'consignmentDateTime'           => '',
        //业务类型代码
        'businessTypeCode'              => '',
        //必填，本单货物的发货时间YYYYMMDDhhmmss
        'despatchActualDateTime'        => '',
        //必填，本单货物的收货时间YYYYMMDDhhmmss
        'goodsReceiptDateTime'          => '',
        //必填，托运人与网络货运经营者签订运输合同确定的运费金额，货币单位为人民币（元），保留3 位小数，如整数的话，以.000 填充。如是一笔业务分几辆车运，需将托运人针对这笔业务付给网络货运经营者的运输费用分摊到每辆车上。
        'totalMonetaryAmount'           => '',
        //托运人信息
        'consignorInfo'                 => [],
        //收货人信息
        'consigneeInfo'                 => [],
        //车辆信息
        'vehicleInfo'                   => [],
        //实际承运人信息
        'actualCarrierInfo'             => [],
        //保险信息
        'insuranceInformation'          => [
            // 保单号 或 none
            'policyNumber'         => 'none',
            // 保险公司代码 或 none
            'insuranceCompanyCode' => 'none',
        ],
        //备注
        'remark'                        => '',
    ];

    public function formatData()
    {
        $this->items['totalMonetaryAmount'] = number_format($this->items['totalMonetaryAmount'], 3, '.', '');
        //车辆总数
        $this->items['vehicleAmount'] = count($this->items['vehicleInfo']);
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        //判断有没有为空的必填参数
        $validate = new Validate();
        $validate->check($this->items, [
            'originalDocumentNumber'        => 'require|max:35',
            'shippingNoteNumber'            => 'require|max:20',
            'serialNumber'                  => 'require|number|max:4',
            'transportTypeCode'             => 'require|number|max:2',
            'sendToProDateTime'             => 'require|dateFormat:YmdHis',
            'carrier'                       => 'require|max:512',
            'unifiedSocialCreditIdentifier' => 'require|length:18',
            'permitNumber'                  => 'require|max:50',
            'consignmentDateTime'           => 'require|dateFormat:YmdHis',
            'businessTypeCode'              => 'require|max:7',
            'despatchActualDateTime'        => 'require|dateFormat:YmdHis',
            'goodsReceiptDateTime'          => 'require|dateFormat:YmdHis',
            'consignorInfo'                 => 'require|array',
            'consigneeInfo'                 => 'require|array',
            'totalMonetaryAmount'           => 'require|float|>:0',
            'vehicleInfo'                   => 'require|array',
            'actualCarrierInfo'             => 'require|array',
            'remark'                        => 'max:256',
        ]);
    }
}