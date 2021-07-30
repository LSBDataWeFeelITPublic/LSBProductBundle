<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\StorageInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class StorageFactory
 * @package LSB\ProductBundle\Factory
 */
class StorageFactory extends BaseFactory implements StorageFactoryInterface
{

    /**
     * @return StorageInterface
     */
    public function createNew(): StorageInterface
    {
        return parent::createNew();
    }

}
