<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/27
 * Time:上午8:36
 * Description:
 */

namespace fjwccy\content\flow;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

class ShippingNoteList extends Content
{
    protected array $items = [
        'shippingNoteNumber'  => '',//运单号
        'totalMonetaryAmount' => '',//总金额
        'serialNumber'        => '',//分段单号 默认0000
    ];

    public function formatData()
    {
        $this->items['totalMonetaryAmount'] = number_format($this->items['totalMonetaryAmount'], 3, '.', '');
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        $validate = new Validate();
        $validate->check($this->items,[
            'shippingNoteNumber'  => 'require|max:20',
            'serialNumber'        => 'require|max:4',
            'totalMonetaryAmount' => 'require|float|>:0|<:1000000000000000',
        ]);
    }
}