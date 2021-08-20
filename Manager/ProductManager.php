<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Manager;

use LSB\ProductBundle\Entity\Product;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Factory\ProductFactoryInterface;
use LSB\ProductBundle\Repository\ProductRepositoryInterface;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Manager\ObjectManagerInterface;
use LSB\UtilityBundle\Manager\BaseManager;
use Webmozart\Assert\Assert;

/**
 * Class ProductManager
 * @package LSB\ProductBundle\Service
 */
class ProductManager extends BaseManager
{
    /**
     * ProductManager constructor.
     * @param ObjectManagerInterface $objectManager
     * @param ProductFactoryInterface $factory
     * @param ProductRepositoryInterface $repository
     * @param BaseEntityType|null $form
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductFactoryInterface $factory,
        ProductRepositoryInterface $repository,
        ?BaseEntityType $form
    ) {
        parent::__construct($objectManager, $factory, $repository, $form);
    }

    /**
     * @return ProductInterface|object
     */
    public function createNew(): ProductInterface
    {
        return parent::createNew();
    }

    /**
     * @return ProductFactoryInterface
     */
    public function getFactory(): ProductFactoryInterface
    {
        return parent::getFactory();
    }

    /**
     * @return ProductRepositoryInterface
     * @throws \Exception
     */
    public function getRepository(): ProductRepositoryInterface
    {
        return parent::getRepository();
    }

    public function getProductSetByProductAndUuid(string $productSetUuid, string $productUuid): ?Product
    {
        try {
            Assert::uuid($productSetUuid);
            Assert::uuid($productUuid);

            return $this->getRepository()->getProductSetByProductAndUuid($productSetUuid, $productUuid);
        } catch (\Exception $e) {
        }

        return null;
    }
}