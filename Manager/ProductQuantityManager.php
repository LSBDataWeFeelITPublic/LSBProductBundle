<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\ProductQuantityInterface;
use LSB\ProductBundle\Factory\ProductQuantityFactoryInterface;
use LSB\ProductBundle\Repository\ProductQuantityRepositoryInterface;
use LSB\UtilityBundle\Factory\FactoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
* Class ProductQuantityManager
* @package LSB\ProductBundle\Manager
*/
class ProductQuantityManager extends BaseManager
{

    /**
     * ProductQuantityManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ProductQuantityFactoryInterface $factory
     * @param ProductQuantityRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductQuantityFactoryInterface $factory,
        ProductQuantityRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return ProductQuantityInterface|object
     */
    public function createNew(): ProductQuantityInterface
    {
        return parent::createNew();
    }

    /**
     * @return ProductQuantityFactoryInterface|FactoryInterface
     */
    public function getFactory(): ProductQuantityFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return ProductQuantityRepositoryInterface|RepositoryInterface
     */
    public function getRepository(): ProductQuantityRepositoryInterface
    {
        return parent::getRepository();
    }
}
