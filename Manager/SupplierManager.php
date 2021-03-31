<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\SupplierInterface;
use LSB\ProductBundle\Factory\SupplierFactoryInterface;
use LSB\ProductBundle\Repository\SupplierRepositoryInterface;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Form\BaseEntityType;

/**
 * Class SupplierManager
 * @package LSB\ProductBundle\Manager
 */
class SupplierManager extends BaseManager
{
    /**
     * SupplierManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param SupplierFactoryInterface $factory
     * @param SupplierRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        SupplierFactoryInterface $factory,
        SupplierRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return SupplierInterface|object
     */
    public function createNew(): SupplierInterface
    {
        return parent::createNew();
    }

    /**
     * @return SupplierFactoryInterface
     */
    public function getFactory(): SupplierFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return SupplierRepositoryInterface
     * @throws \Exception
     */
    public function getRepository(): SupplierRepositoryInterface
    {
        return parent::getRepository();
    }

}