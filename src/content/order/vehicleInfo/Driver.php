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

final class Driver extends Content
{
    protected array $items = [
        'driverName'     => '',
        'drivingLicense' => '',
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
            'driverName'     => 'require|max:30',
            'drivingLicense' => 'require|max:18',
        ]);
    }
}