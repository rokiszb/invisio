<?php

namespace App\Response;

use App\Config\Product;

class ProductResponse
{
    private array $parameter1 = Product::PRODUCT_GROUP_1;
    private array $parameter2 = Product::PRODUCT_GROUP_2;


    public function getParameter1(): array
    {
        return $this->parameter1;
    }

    public function setParameter1(array $parameter1): void
    {
        $this->parameter1 = $parameter1;
    }

    public function getParameter2(): array
    {
        return $this->parameter2;
    }

    public function setParameter2(array $parameter2): void
    {
        $this->parameter2 = $parameter2;
    }

}