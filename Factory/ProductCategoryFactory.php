<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\ProductCategoryInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductCategoryFactory
 * @package LSB\ProductBundle\Factory
 */
class ProductCategoryFactory extends BaseFactory implements ProductCategoryFactoryInterface
{

    /**
     * @return ProductCategoryInterface
     */
    public function createNew(): ProductCategoryInterface
    {
        return parent::createNew();
    }

}
