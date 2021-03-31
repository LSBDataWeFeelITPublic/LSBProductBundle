<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Tests;

use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Factory\ProductFactory;
use LSB\ProductBundle\Factory\ProductFactoryInterface;
use LSB\ProductBundle\Manager\ProductManager;
use LSB\ProductBundle\Repository\ProductRepository;
use LSB\ProductBundle\Repository\ProductRepositoryInterface;
use LSB\UtilityBundle\Manager\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductManagerTest
 * @package LSB\ContraBundle\Tests
 */
class ProductManagerTest extends TestCase
{
    /**
     * Assert returned interfaces
     * @throws \Exception
     */
    public function testReturnedInterfaces()
    {
        $objectManagerMock = $this->createMock(ObjectManager::class);
        $productFactoryMock = $this->createMock(ProductFactory::class);
        $productRepositoryMock = $this->createMock(ProductRepository::class);

        $productManager = new ProductManager($objectManagerMock, $productFactoryMock, $productRepositoryMock, null);

        $this->assertInstanceOf(ProductInterface::class, $productManager->createNew());
        $this->assertInstanceOf(ProductFactoryInterface::class, $productManager->getFactory());
        $this->assertInstanceOf(ProductRepositoryInterface::class, $productManager->getRepository());
    }
}
