<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/25
 * Time:下午4:53
 * Description:托运人信息
 */

namespace fjwccy\content\order;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class ConsignorInfo extends Content
{
    protected array $items = [
        'consignor'              => '', //名称
        'consignorId'            => '', //证件号码
        'placeOfLoading'         => '', //货物的装货的地点
        'countrySubdivisionCode' => '', //装货地点的国家行政区划代码
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
            'consignor'              => 'require|max:512',
            'consignorId'            => 'require|max:35',
            'placeOfLoading'         => 'require|max:256',
            'countrySubdivisionCode' => 'require|max:12',
        ]);
    }
}