<?php

namespace App\Config;

class Product
{
    public const PRODUCT_GROUP_1 = ['A', 'B', 'C'];
    public const PRODUCT_GROUP_2 = ['X', 'Y', 'Z'];

    public const INCOMPATIBLE_PRODUCTS = [
        'A' => 'Y',
        'Y' => 'A',
        'C' => 'Z',
        'Z' => 'C',
    ];

    public function hasIncompatibleProducts(string $param): bool
    {
        return (isset(self::INCOMPATIBLE_PRODUCTS[$param]));
    }

    public function getIncompatibleProducts(string $param): string
    {
        return self::INCOMPATIBLE_PRODUCTS[$param];
    }
}