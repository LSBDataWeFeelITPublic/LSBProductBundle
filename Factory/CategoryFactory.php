<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\CategoryInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class CategoryFactory
 * @package LSB\ProductBundle\Factory
 */
class CategoryFactory extends BaseFactory implements CategoryFactoryInterface
{

    /**
     * @return CategoryInterface
     */
    public function createNew(): CategoryInterface
    {
        return parent::createNew();
    }

}
