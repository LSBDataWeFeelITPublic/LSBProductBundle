<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Tests;

use LSB\ProductBundle\Entity\ProductSetProductInterface;
use LSB\ProductBundle\Factory\ProductSetProductFactory;
use LSB\ProductBundle\Factory\ProductSetProductFactoryInterface;
use LSB\ProductBundle\Manager\ProductSetProductManager;
use LSB\ProductBundle\Repository\ProductSetProductRepository;
use LSB\ProductBundle\Repository\ProductSetProductRepositoryInterface;
use LSB\UtilityBundle\Manager\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductSetProductManagerTest
 * @package LSB\ProductBundle\Tests
 */
class ProductSetProductManagerTest extends TestCase
{
    /**
     * Assert returned interfaces
     * @throws \Exception
     */
    public function testReturnedInterfaces()
    {
        $objectManagerMock = $this->createMock(ObjectManager::class);
        $productSetProductFactoryMock = $this->createMock(ProductSetProductFactory::class);
        $productSetProductRepositoryMock = $this->createMock(ProductSetProductRepository::class);
        $productSetProductManager = new ProductSetProductManager($objectManagerMock, $productSetProductFactoryMock, $productSetProductRepositoryMock, null);

        $this->assertInstanceOf(ProductSetProductInterface::class, $productSetProductManager->createNew());
        $this->assertInstanceOf(ProductSetProductFactoryInterface::class, $productSetProductManager->getFactory());
        $this->assertInstanceOf(ProductSetProductRepositoryInterface::class, $productSetProductManager->getRepository());
    }
}
