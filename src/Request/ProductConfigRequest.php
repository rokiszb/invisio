<?php

namespace App\Request;

use App\Config\Product;
use Symfony\Component\Validator\Constraints as Assert;

class ProductConfigRequest
{
    #[Assert\Choice(Product::PRODUCT_GROUP_1)]
    public ?string $parameter1;

    #[Assert\Choice(Product::PRODUCT_GROUP_2)]
    public ?string $parameter2;
}