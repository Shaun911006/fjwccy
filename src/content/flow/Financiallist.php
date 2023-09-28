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

class Financiallist extends Content
{
    protected array $items = [
        'paymentMeansCode' => '',//付款方式代码
        'recipient'        => '',//收款方名称
        'receiptAccount'   => '',//收款账户信息
        'bankCode'         => '',//收款方银行代码
        'sequenceCode'     => '',//流水号/序列号
        'monetaryAmount'   => '',//实际支付金额
        'dateTime'         => '',//日期时间
    ];

    public function formatData()
    {
        $this->items['monetaryAmount'] = number_format($this->items['monetaryAmount'], 3, '.', '');
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        $validate = new Validate();
        $validate->check($this->items, [
            'paymentMeansCode' => 'require|max:3',
            'recipient'        => 'require|max:512',
            'receiptAccount'   => 'require|max:512',
            'bankCode'         => 'require|max:11',
            'sequenceCode'     => 'require|max:50',
            'monetaryAmount'   => 'require|float|>:0|<:1000000000000000',
            'dateTime'         => 'require|dateFormat:YmdHis',
        ]);
    }
}