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

final class Driver extends Content
{
    protected array $items = [
        'driverName'               => '', //姓名
        'drivingLicense'           => '', //身份证号
        'vehicleClass'             => '', //准驾车型
        'issuingOrganizations'     => '', //驾驶证发证机关
        'validPeriodFrom'          => '', //有效期自YYYYMMDD
        'validPeriodTo'            => '', //有效期到YYYYMMDD
        'qualificationCertificate' => '', //从业资格证号
        'telephone'                => '', //电话号码
        'remark'                   => '', //备注
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
            'driverName'               => 'require|max:30',
            'drivingLicense'           => 'require|length:18',
            'vehicleClass'             => 'require|max:20',
            'issuingOrganizations'     => 'require|max:128',
            'validPeriodFrom'          => 'require|dateFormat:Ymd',
            'validPeriodTo'            => 'require|dateFormat:Ymd',
            'qualificationCertificate' => 'require|max:19',
            'telephone'                => 'require|max:18',
            'remark'                   => 'max:256',
        ]);
    }
}