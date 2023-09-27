<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/25
 * Time:下午5:02
 * Description:实际承运人信息
 */

namespace fjwccy\content\order;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class ActualCarrierInfo extends Content
{
    protected array $items = [
        'actualCarrierName'            => '', //名称
        'actualCarrierId'              => '', //实际承运人统一社会信用代码或证件号码
        'actualCarrierBusinessLicense' => '', //道路运输经营许可证或车籍地6位行政区域代码+000000
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
            'actualCarrierName'            => 'require|max:256',
            'actualCarrierId'              => 'require|max:50',
            'actualCarrierBusinessLicense' => 'require|max:50',
        ]);
    }
}