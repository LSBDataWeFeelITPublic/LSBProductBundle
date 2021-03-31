<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\SupplierInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductFactory
 * @package LSB\ProductBundle\Factory
 */
class SupplierFactory extends BaseFactory implements SupplierFactoryInterface
{
    /**
     * @return SupplierInterface
     */
    public function createNew(): SupplierInterface
    {
        return parent::createNew();
    }
}