<?php

namespace App\Classes\PromocodeType;

interface PromoCodeTypeInterface
{
    /**
     * @return string
     */
    public function generate(): string;

}