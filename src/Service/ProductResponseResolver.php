<?php

namespace App\Service;

use App\Config\Product as ProductConfig;
use App\Request\ProductConfigRequest;
use App\Response\ProductResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductResponseResolver
{
    public function __construct(private ProductConfig $product)
    {
    }

    public function getProductResponse(ProductConfigRequest $productRequest): ProductResponse
    {
        $productResponse = new ProductResponse();

        if (null === $productRequest->parameter1 && null === $productRequest->parameter2) {
            return $productResponse;
        }

        if (in_array($productRequest->parameter1, ProductConfig::PRODUCT_GROUP_1, true)) {
            $productResponse->setParameter1([$productRequest->parameter1]);
            if ($this->product->hasIncompatibleProducts($productRequest->parameter1)) {
                $incompatible = $this->product->getIncompatibleProducts($productRequest->parameter1);
                $group2 = array_diff(ProductConfig::PRODUCT_GROUP_2, [$incompatible]);
                $productResponse->setParameter2($group2);
            }

            return $productResponse;
        }

        if (in_array($productRequest->parameter2, ProductConfig::PRODUCT_GROUP_2, true)) {
            $productResponse->setParameter2([$productRequest->parameter2]);
            if ($this->product->hasIncompatibleProducts($productRequest->parameter2)) {
                $incompatible = $this->product->getIncompatibleProducts($productRequest->parameter2);
                $group1 = array_diff(ProductConfig::PRODUCT_GROUP_1, [$incompatible]);
                $productResponse->setParameter1($group1);
            }

            return $productResponse;
        }

        throw new NotFoundResourceException();
    }
}