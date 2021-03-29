<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\ProductSetProductInterface;
use LSB\ProductBundle\Factory\ProductSetProductFactoryInterface;
use LSB\ProductBundle\Repository\ProductSetProductRepositoryInterface;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Form\BaseEntityType;

/**
 * Class ProductSetProductManager
 * @package LSB\ProductBundle\Manager
 */
class ProductSetProductManager extends BaseManager
{
    /**
     * AssortmentGroupManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ProductSetProductFactoryInterface $factory
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductSetProductFactoryInterface $factory,
        ProductSetProductRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return ProductSetProductInterface|object
     */
    public function createNew(): ProductSetProductInterface
    {
        return parent::createNew();
    }
}