<?php


namespace App\Classes\PromocodeType;


use App\Entity\PromoCode;

class BasicPromoCodeType implements PromoCodeTypeInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        //6 символов, достаточно для большкого количества вариантов - но при этом и легко запомнить
        $code = base64_encode(openssl_random_pseudo_bytes(10));
        $code = substr($code, 0, 6);
        $code = str_replace(['=', '+', '/', '\\'], 'Z', $code);

        return $code;
    }
}