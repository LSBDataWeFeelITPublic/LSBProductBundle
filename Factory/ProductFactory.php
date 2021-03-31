<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\ProductInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductFactory
 * @package LSB\ProductBundle\Factory
 */
class ProductFactory extends BaseFactory implements ProductFactoryInterface
{
    /**
     * @return ProductInterface
     */
    public function createNew(): ProductInterface
    {
        return parent::createNew();
    }
}