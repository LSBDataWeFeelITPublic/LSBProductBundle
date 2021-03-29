<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Factory\ProductFactoryInterface;
use LSB\ProductBundle\Repository\ProductRepositoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use Symfony\Component\Form\AbstractType;

/**
 * Class ProductManager
 * @package LSB\ProductBundle\Service
 */
class ProductManager extends BaseManager
{
    /**
     * ProductManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ProductFactoryInterface $factory
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductFactoryInterface $factory,
        ProductRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return ProductInterface|object
     */
    public function createNew(): ProductInterface
    {
        return parent::createNew();
    }
}