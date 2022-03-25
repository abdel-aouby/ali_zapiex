<?php

use Tester\Assert;

class CheapestTest extends Aiti_Testing_Model_TestCase {


    protected function _getZapiexShippingApiResponse(): array {
        return [
            "statusCode" => 200,
            "data" => [
                "shipFrom" => "CN",
                "isAvailableForSelectedCountries" => true,
                "currency" => "USD",
                "carriers" => [
                    [
                        "company" => [
                            "id" => "AE_CN_SUPER_ECONOMY_G",
                            "name" => "Cainiao Super Economy Global"
                        ],
                        "hasTracking" => false,
                        "price" => [
                            "value" => 0.61
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-05-28",
                        "deliveryTimeInDays" => [
                            "min" => 64,
                            "max" => 64
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "CAINIAO_SUPER_ECONOMY",
                            "name" => "Cainiao Super Economy"
                        ],
                        "hasTracking" => false,
                        "price" => [
                            "value" => 0.68
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-05-28",
                        "deliveryTimeInDays" => [
                            "min" => 64,
                            "max" => 64
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "CAINIAO_ECONOMY",
                            "name" => "AliExpress Saver Shipping"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 1.68
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-05-12",
                        "deliveryTimeInDays" => [
                            "min" => 48,
                            "max" => 48
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "CAINIAO_STANDARD",
                            "name" => "AliExpress Standard Shipping"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 3.33
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-04-25",
                        "deliveryTimeInDays" => [
                            "min" => 31,
                            "max" => 31
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "CAINIAO_PREMIUM",
                            "name" => "AliExpress Premium Shipping"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 21.56
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-04-26",
                        "deliveryTimeInDays" => [
                            "min" => 18,
                            "max" => 32
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "FEDEX",
                            "name" => "Fedex IP"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 67.64
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-04-16",
                        "deliveryTimeInDays" => [
                            "min" => 14,
                            "max" => 22
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "DHL",
                            "name" => "DHL"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 74.03
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-04-16",
                        "deliveryTimeInDays" => [
                            "min" => 14,
                            "max" => 22
                        ]
                    ],
                    [
                        "company" => [
                            "id" => "UPS",
                            "name" => "UPS Express Saver"
                        ],
                        "hasTracking" => true,
                        "price" => [
                            "value" => 74.99
                        ],
                        "hasDiscount" => false,
                        "discountPercentage" => 0,
                        "estimatedDeliveryDate" => "2022-04-11",
                        "deliveryTimeInDays" => [
                            "min" => 9,
                            "max" => 17
                        ]
                    ]
                ]
            ]
        ];
    }

    public function testCheapestShippingMethodFromZapiexResponse() {
        $response = $this->_getZapiexShippingApiResponse();

        shuffle($response['data']['carriers']);

        $cheapestShipping = Mage::getModel('aiti_zapiex/api_productShipping')->getCheapestShipping($response);

        Assert::same(0.61,(float) $cheapestShipping['shipping']);
        Assert::same('AE_CN_SUPER_ECONOMY_G',(string) $cheapestShipping['shipping_method_id']);
        Assert::same('Cainiao Super Economy Global',(string) $cheapestShipping['shipping_method']);
        Assert::same('USD',(string) $cheapestShipping['currency']);

    }

}