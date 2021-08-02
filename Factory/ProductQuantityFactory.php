<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\ProductQuantityInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductQuantityFactory
 * @package LSB\ProductBundle\Factory
 */
class ProductQuantityFactory extends BaseFactory implements ProductQuantityFactoryInterface
{

    /**
     * @return ProductQuantityInterface
     */
    public function createNew(): ProductQuantityInterface
    {
        return parent::createNew();
    }

}
