<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\CategoryInterface;
use LSB\ProductBundle\Factory\CategoryFactoryInterface;
use LSB\ProductBundle\Repository\CategoryRepositoryInterface;
use LSB\UtilityBundle\Factory\FactoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
* Class CategoryManager
* @package LSB\ProductBundle\Manager
*/
class CategoryManager extends BaseManager
{

    /**
     * CategoryManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param CategoryFactoryInterface $factory
     * @param CategoryRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        CategoryFactoryInterface $factory,
        CategoryRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return CategoryInterface|object
     */
    public function createNew(): CategoryInterface
    {
        return parent::createNew();
    }

    /**
     * @return CategoryFactoryInterface|FactoryInterface
     */
    public function getFactory(): CategoryFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return CategoryRepositoryInterface|RepositoryInterface
     */
    public function getRepository(): CategoryRepositoryInterface
    {
        return parent::getRepository();
    }
}
