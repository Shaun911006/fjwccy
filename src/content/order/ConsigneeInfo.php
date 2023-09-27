<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/25
 * Time:下午4:55
 * Description:收货方信息
 */

namespace fjwccy\content\order;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class ConsigneeInfo extends Content
{
    protected array $items = [
        'consignee'              => '', //收货方名称
        'consigneeId'            => '', //收货方证件号码（选填）
        'goodsReceiptPlace'      => '', //收货地址
        'countrySubdivisionCode' => '', //收货地点的国家行政区划代码或国别代码
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
        $validate->check($this->items, [
            'consignee'              => 'require|max:512',
            'consigneeId'            => 'max:35',
            'goodsReceiptPlace'      => 'require|max:256',
            'countrySubdivisionCode' => 'require|max:12',
        ]);
    }
}