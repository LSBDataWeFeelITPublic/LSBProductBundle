<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\ProductBundle\Factory\AssortmentGroupFactoryInterface;
use LSB\ProductBundle\Repository\AssortmentGroupRepositoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
/**
 * Class AssortmentGroupManager
 * @package LSB\ProductBundle\Manager
 */
class AssortmentGroupManager extends BaseManager
{
    /**
     * AssortmentGroupManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param AssortmentGroupFactoryInterface $factory
     * @param AssortmentGroupRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        AssortmentGroupFactoryInterface $factory,
        AssortmentGroupRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return AssortmentGroupInterface|object
     */
    public function createNew(): AssortmentGroupInterface
    {
        return parent::createNew();
    }

    /**
     * @return AssortmentGroupFactoryInterface
     */
    public function getFactory(): AssortmentGroupFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return AssortmentGroupRepositoryInterface
     * @throws \Exception
     */
    public function getRepository(): AssortmentGroupRepositoryInterface
    {
        return parent::getRepository();
    }
}