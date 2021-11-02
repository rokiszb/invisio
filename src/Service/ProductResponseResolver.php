<?php

namespace App\Service;

use App\Config\Product as ProductConfig;
use App\Response\ProductResponse;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProductResponseResolver
{
    public function __construct(private ProductConfig $product)
    {
    }

    public function getProductResponse(string $parameter1 = null, string $parameter2 = null): ProductResponse
    {
        $productResponse = new ProductResponse();

        if (null === $parameter1 && null === $parameter2) {
            return $productResponse;
        }

        if (in_array($parameter1, ProductConfig::PRODUCT_GROUP_1, true)) {
            $productResponse->setParameter1([$parameter1]);
            if ($this->product->hasIncompatibleProducts($parameter1)) {
                $incompatible = $this->product->getIncompatibleProducts($parameter1);
                $group2 = array_diff(ProductConfig::PRODUCT_GROUP_2, [$incompatible]);
                $productResponse->setParameter2($group2);
            }

            return $productResponse;
        }

        if (in_array($parameter2, ProductConfig::PRODUCT_GROUP_2, true)) {
            $productResponse->setParameter2([$parameter2]);
            if ($this->product->hasIncompatibleProducts($parameter2)) {
                $incompatible = $this->product->getIncompatibleProducts($parameter2);
                $group1 = array_diff(ProductConfig::PRODUCT_GROUP_1, [$incompatible]);
                $productResponse->setParameter1($group1);
            }

            return $productResponse;
        }

        throw new NotFoundResourceException();
    }
}