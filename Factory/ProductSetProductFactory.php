<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\ProductSetProductInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductSetProductFactory
 * @package LSB\ProductBundle\Factory
 */
class ProductSetProductFactory extends BaseFactory implements ProductSetProductFactoryInterface
{
    /**
     * @return ProductSetProductInterface
     */
    public function createNew(): ProductSetProductInterface
    {
        return parent::createNew();
    }
}