<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\ProductBundle\Factory\AssortmentGroupFactoryInterface;
use LSB\ProductBundle\Repository\AssortmentGroupRepositoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use Symfony\Component\Form\AbstractType;

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
}