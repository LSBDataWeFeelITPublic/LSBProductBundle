<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\StorageInterface;
use LSB\ProductBundle\Factory\StorageFactoryInterface;
use LSB\ProductBundle\Repository\StorageRepositoryInterface;
use LSB\UtilityBundle\Factory\FactoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
* Class StorageManager
* @package LSB\ProductBundle\Manager
*/
class StorageManager extends BaseManager
{

    /**
     * StorageManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param StorageFactoryInterface $factory
     * @param StorageRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        StorageFactoryInterface $factory,
        StorageRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return StorageInterface|object
     */
    public function createNew(): StorageInterface
    {
        return parent::createNew();
    }

    /**
     * @return StorageFactoryInterface|FactoryInterface
     */
    public function getFactory(): StorageFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return StorageRepositoryInterface|RepositoryInterface
     */
    public function getRepository(): StorageRepositoryInterface
    {
        return parent::getRepository();
    }
}
