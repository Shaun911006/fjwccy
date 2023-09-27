<?php
/**
 * Author:Shaun·Yang
 * Date:2023/9/25
 * Time:下午5:35
 * Description:
 */

namespace fjwccy\content\order\vehicleInfo;

use fjwccy\content\Content;
use fjwccy\exception\ValidateException;
use fjwccy\helper\Validate;

final class GoodsInfo extends Content
{
    protected array $items = [
        'descriptionOfGoods'          => '',
        'cargoTypeClassificationCode' => '',
        'goodsItemGrossWeight'        => '',
    ];

    public function formatData()
    {
        $this->items['goodsItemGrossWeight'] = number_format($this->items['goodsItemGrossWeight'], 3, '.', '');
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        $validate = new Validate();
        $validate->check($this->items,[
            'descriptionOfGoods'          => 'require|max:512',
            'cargoTypeClassificationCode' => 'require|max:4',
            'goodsItemGrossWeight'        => 'require|float|>:0|<:1000000000000',
        ]);
    }
}