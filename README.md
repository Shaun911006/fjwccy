# 福建无车承运人上报

## 异常

- ContentException 报文体中有无效字段
- ValidateException 报文字段验证有问题
- WccyTokenException 令牌失效
- WccyException 请求/加密/其他异常等


### 上报司机信息

```php
use fjwccy\WccyClient;
use fjwccy\content\Driver;

$client = new WccyClient($conf);

$token  = $client->getToken(); //token可以缓存2小时

$driver = new Driver([
    'driverName'               => '张三',
    'drivingLicense'           => '342125197805990912',
    'vehicleClass'             => 'B2',
    'issuingOrganizations'     => '运管局',
    'validPeriodFrom'          => '20191112',
    'validPeriodTo'            => '20191112',
    'qualificationCertificate' => '342125197805990912',
    'telephone'                => '13888888888',
]);
$res = $client->send($token, WccyClient::JSY, $driver->toArray());
```

### 上报车辆信息

```php
use fjwccy\WccyClient;
use fjwccy\content\Truck;

$client = new WccyClient($conf);

$token  = $client->getToken(); //token可以缓存2小时

$truck = new Truck([
    'vehicleNumber'                  => '鄂FTC360',
    'vehiclePlateColorCode'          => '2',
    'vehicleType'                    => 'H11',
    'owner'                          => '深圳市神风运输有限公司',
    'useCharacter'                   => '道路普通货物运输',
    'vin'                            => '123456789',
    'issuingOrganizations'           => '运管局',
    'registerDate'                   => '20191112',
    'issueDate'                      => '20191112',
    'vehicleEnergyType'              => 'A',
    'vehicleTonnage'                 => '21.30',
    'grossMass'                      => '29.90',
    'roadTransportCertificateNumber' => '420606208298',
    'trailerVehiclePlateNumber'      => '',
    'remark'                         => '',
]);
$res = $client->send($token, WccyClient::CL, $truck->toArray());
```

### 上报运单信息

```php
use fjwccy\WccyClient;
use fjwccy\content\Order;
use fjwccy\content\order\ConsignorInfo;
use fjwccy\content\order\ConsigneeInfo;
use fjwccy\content\order\VehicleInfo;
use fjwccy\content\order\vehicleInfo\Driver;
use fjwccy\content\order\vehicleInfo\GoodsInfo;
use fjwccy\content\order\ActualCarrierInfo;

$client = new WccyClient($conf);

$token  = $client->getToken(); //token可以缓存2小时

$order = new Order([
    'originalDocumentNumber'        => '9999999999',
    'shippingNoteNumber'            => '4564s6af5d4f6s5',
    'serialNumber'                  => '0103',
    'vehicleAmount'                 => 1,
    'transportTypeCode'             => 1,
    'sendToProDateTime'             => '20190808112233',
    'carrier'                       => '天地轮公司',
    'unifiedSocialCreditIdentifier' => '99910109MA1G566Y32',
    'permitNumber'                  => '510114118554',
    'consignmentDateTime'           => '20190808112233',
    'businessTypeCode'              => '1002996',
    'despatchActualDateTime'        => '20190808112233',
    'goodsReceiptDateTime'          => '20190808112233',
    'totalMonetaryAmount'           => '999.999',
    //发货人
    'consignorInfo' => new ConsignorInfo([
        'consignor'              => '张玉', //名称
        'consignorId'            => '99910109MA1G566Y32', //证件号码
        'placeOfLoading'         => '北京市朝阳区安华西里8号楼', //货物的装货的地点
        'countrySubdivisionCode' => '110105', //装货地点的国家行政区划代码
    ]),
    //收货人
    'consigneeInfo' => new ConsigneeInfo([
        'consignee'              => '张彦', //收货方名称
        'consigneeId'            => '99910109991G566Y33', //收货方证件号码（选填）
        'goodsReceiptPlace'      => '北京市朝阳区安华西里88号楼', //收货地址
        'countrySubdivisionCode' => '110105', //收货地点的国家行政区划代码或国别代码
    ]),
    //车辆信息
    'vehicleInfo' => new VehicleInfo([
        'vehiclePlateColorCode'         => 1,//车牌颜色代码
        'vehicleNumber'                 => '京AM618挂',//车辆牌照
        'despatchActualDateTime'        => '',//发货时间YYYYMMDDhhmmss
        'goodsReceiptDateTime'          => '',//收货日期时间
        'placeOfLoading'                => '',//装货地址
        'goodsReceiptPlace'             => '',//收货地址
        'loadingCountrySubdivisionCode' => '',//装货地址编码
        'receiptCountrySubdivisionCode' => '',//收货地址编码
        //添加司机
        'driver' => [
            (new Driver([
                'driverName'     => '李四',
                'drivingLicense' => '352229199010112099',
            ]))
        ],
        //货物信息
        'goodsInfo' => [
            (new GoodsInfo([
                'descriptionOfGoods'          => '管件',
                'cargoTypeClassificationCode' => '0600',
                'goodsItemGrossWeight'        => '99000.999',
            ]))
        ],
    ]),

    'actualCarrierInfo' => new ActualCarrierInfo([
        'actualCarrierName'            => '亨利', //名称
        'actualCarrierId'              => '88910109881G566V32', //实际承运人统一社会信用代码或证件号码
        'actualCarrierBusinessLicense' => '88910109881G566V32', //道路运输经营许可证或车籍地6位行政区域代码+000000
    ]),
]);
$res = $client->send($token, WccyClient::YD, $order->toArray());
```

### 上报资金流水信息

```php
use fjwccy\WccyClient;
use fjwccy\content\Flow;

$client = new WccyClient($conf);

$token  = $client->getToken(); //token可以缓存2小时

$flow = new Flow([
    'documentNumber'        => '456468546',
    'sendToProDateTime'     => '20190819112233',
    'carrier'               => '韩丽',
    'actualCarrierId'       => '88910109881G566V32',
    'vehicleNumber'         => '晋AF3727',
    'vehiclePlateColorCode' => '1',
    //添加运单信息
    'shippingNoteList'      => [
        [
            "shippingNoteNumber"  => "456123123132",
            "serialNumber"        => "0101",
            "totalMonetaryAmount" => '999.999'
        ],
        [
            "shippingNoteNumber"  => "456123123132",
            "serialNumber"        => "0101",
            "totalMonetaryAmount" => 888
        ]
    ],
    //添加打款信息
    'financiallist'         => [
        [
            "paymentMeansCode" => "42",
            "recipient"        => "天顺",
            "receiptAccount"   => "6217222806000169033",
            "bankCode"         => "ZXBK",
            "sequenceCode"     => "123456789987",
            "monetaryAmount"   => 999.999,
            "dateTime"         => "20190819112233"
        ],
        [
            "paymentMeansCode" => "32",
            "recipient"        => "天顺",
            "receiptAccount"   => "6217222806000169033",
            "bankCode"         => "ZXBK",
            "sequenceCode"     => "123456789987",
            "monetaryAmount"   => 777.777,
            "dateTime"         => "20190819112233"
        ]
    ],
    'remark'                => '',
]);
$res = $client->send($token, WccyClient::ZJ, $flow->toArray());
```