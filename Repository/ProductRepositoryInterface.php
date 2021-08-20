<?php

namespace LSB\ProductBundle\Repository;

use LSB\ProductBundle\Entity\ProductInterface;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
 * Class ProductRepository
 * @package LSB\ProductBundle\Repository
 */
interface ProductRepositoryInterface extends RepositoryInterface
{
    public function getByNumber(): ?ProductInterface;

    public function getProductSetByProductAndUuid(string $productSetUuid, string $productUuid): ?ProductInterface;
}