<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\ProductCategoryInterface;
use LSB\ProductBundle\Factory\ProductCategoryFactoryInterface;
use LSB\ProductBundle\Repository\ProductCategoryRepositoryInterface;
use LSB\UtilityBundle\Factory\FactoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
* Class ProductCategoryManager
* @package LSB\ProductBundle\Manager
*/
class ProductCategoryManager extends BaseManager
{

    /**
     * ProductCategoryManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ProductCategoryFactoryInterface $factory
     * @param ProductCategoryRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductCategoryFactoryInterface $factory,
        ProductCategoryRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return ProductCategoryInterface|object
     */
    public function createNew(): ProductCategoryInterface
    {
        return parent::createNew();
    }

    /**
     * @return ProductCategoryFactoryInterface|FactoryInterface
     */
    public function getFactory(): ProductCategoryFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return ProductCategoryRepositoryInterface|RepositoryInterface
     */
    public function getRepository(): ProductCategoryRepositoryInterface
    {
        return parent::getRepository();
    }
}
