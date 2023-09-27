<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/25
 * Time:下午5:01
 * Description:车辆信息
 */

namespace fjwccy\content\order;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class VehicleInfo extends Content
{
    protected array $items = [
        'vehiclePlateColorCode'         => '',//车牌颜色代码
        'vehicleNumber'                 => '',//车辆牌照
        'despatchActualDateTime'        => '',//发货时间YYYYMMDDhhmmss
        'goodsReceiptDateTime'          => '',//收货日期时间
        'placeOfLoading'                => '',//装货地址
        'goodsReceiptPlace'             => '',//收货地址
        'loadingCountrySubdivisionCode' => '',//装货地址编码
        'receiptCountrySubdivisionCode' => '',//收货地址编码
        'driver'                        => [],
        'goodsInfo'                     => [],
    ];

    public function formatData()
    {
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        $validate = new Validate();
        $validate->check($this->items,[
            'vehicleNumber'                 => 'require|max:35',
            'vehiclePlateColorCode'         => 'require|max:2',
            'despatchActualDateTime'        => 'require|dateFormat:YmdHis',
            'goodsReceiptDateTime'          => 'require|dateFormat:YmdHis',
            'placeOfLoading'                => 'require|max:256',
            'loadingCountrySubdivisionCode' => 'require|max:12',
            'goodsReceiptPlace'             => 'require|max:256',
            'receiptCountrySubdivisionCode' => 'require|max:12',
            'driver'                        => 'require|array',
            'goodsInfo'                     => 'require|array',
        ]);
    }
}