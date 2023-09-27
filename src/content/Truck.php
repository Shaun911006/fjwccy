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

final class Truck extends Content
{
    protected array $items = [
        'vehicleNumber'                  => '', //车牌号必填
        'vehiclePlateColorCode'          => '', //车牌颜色代码
        'vehicleType'                    => '', //车辆类型代码
        'owner'                          => '', //所有人
        'useCharacter'                   => '', //使用性质
        'vin'                            => '', //选填 车辆识别代码
        'issuingOrganizations'           => '', //发证机关
        'registerDate'                   => '', //注册日期 YYYYMMDD
        'issueDate'                      => '', //发证日期 YYYYMMDD
        'vehicleEnergyType'              => '', //车辆能源类型
        'vehicleTonnage'                 => '', //核定载质量  2位小数 单位：吨
        'grossMass'                      => '', //必填，车辆总质量，默认单位：吨，保留两位小数，如整数的话，以.00填充。小数点不计入总长。
        'roadTransportCertificateNumber' => '', //道路运输证号 必填，总质量4.5 吨及以下普通货运车辆的，可填“车籍地6 位行政区域代码+000000”
        'trailerVehiclePlateNumber'      => '', //挂车牌照号 选填
        'remark'                         => '',
    ];

    public function formatData()
    {
        $this->items['vehicleTonnage'] = number_format((float)$this->items['vehicleTonnage'], 2, '.', '');
        $this->items['grossMass']      = number_format((float)$this->items['grossMass'], 2, '.', '');
    }

    /**
     * @throws ValidateException
     */
    public function validateData()
    {
        $validate = new Validate();
        $validate->check($this->items, [
            'vehicleNumber'                  => 'require|max:35',
            'vehiclePlateColorCode'          => 'require|max:2',
            'vehicleType'                    => 'require|max:3',
            'owner'                          => 'max:128',
            'useCharacter'                   => 'max:20',
            'vin'                            => 'max:32',
            'issuingOrganizations'           => 'max:128',
            'registerDate'                   => 'dateFormat:Ymd',
            'issueDate'                      => 'dateFormat:Ymd',
            'vehicleEnergyType'              => 'require|max:12',
            'vehicleTonnage'                 => 'require|float|>:0|<:10000000',
            'grossMass'                      => 'require|float|>:0|<:10000000',
            'roadTransportCertificateNumber' => 'require|max:20',
            'trailerVehiclePlateNumber'      => 'max:35',
            'remark'                         => 'max:256',
        ]);
        //总质量4.5吨及以下普通货运车辆以下为必填
        if (bccomp($this->items['grossMass'], '4.5', 2) !== 1) {
            $validate->check($this->items, [
                'owner'                => 'require',
                'useCharacter'         => 'require',
                'vin'                  => 'require',
                'issuingOrganizations' => 'require',
                'registerDate'         => 'require',
                'issueDate'            => 'require',
            ]);
        }
    }
}