<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class AssortmentGroupFactory
 * @package LSB\ProductBundle\Factory
 */
class AssortmentGroupFactory extends BaseFactory implements AssortmentGroupFactoryInterface
{
    /**
     * @return AssortmentGroupInterface
     */
    public function createNew(): AssortmentGroupInterface
    {
        return parent::createNew();
    }
}