<?php


namespace App\Classes\PromocodeType;


class NumberPromoCodeType implements PromoCodeTypeInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return mt_rand(100000, 999999);
    }
}