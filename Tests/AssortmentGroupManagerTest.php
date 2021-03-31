<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Tests;

use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\ProductBundle\Factory\AssortmentGroupFactory;
use LSB\ProductBundle\Factory\AssortmentGroupFactoryInterface;
use LSB\ProductBundle\Manager\AssortmentGroupManager;
use LSB\ProductBundle\Repository\AssortmentGroupRepository;
use LSB\ProductBundle\Repository\AssortmentGroupRepositoryInterface;
use LSB\UtilityBundle\Manager\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class AssortmentGroupManagerTest
 * @package LSB\ProductBundle\Tests
 */
class AssortmentGroupManagerTest extends TestCase
{
    /**
     * Assert returned interfaces
     * @throws \Exception
     */
    public function testReturnedInterfaces()
    {
        $objectManagerMock = $this->createMock(ObjectManager::class);
        $assortmentGroupFactoryMock = $this->createMock(AssortmentGroupFactory::class);
        $assortmentGroupRepositoryMock = $this->createMock(AssortmentGroupRepository::class);

        $assortmentGroupManager = new AssortmentGroupManager($objectManagerMock, $assortmentGroupFactoryMock, $assortmentGroupRepositoryMock, null);

        $this->assertInstanceOf(AssortmentGroupInterface::class, $assortmentGroupManager->createNew());
        $this->assertInstanceOf(AssortmentGroupFactoryInterface::class, $assortmentGroupManager->getFactory());
        $this->assertInstanceOf(AssortmentGroupRepositoryInterface::class, $assortmentGroupManager->getRepository());
    }
}
