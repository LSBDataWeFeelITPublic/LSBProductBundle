<?php
declare(strict_types=1);

namespace LSB\ProductBundle\DependencyInjection;

use Entity\Repository\CategoryRepository;
use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\ProductBundle\Entity\AssortmentGroupTranslationInterface;
use LSB\ProductBundle\Entity\CategoryInterface;
use LSB\ProductBundle\Entity\CategoryTranslationInterface;
use LSB\ProductBundle\Entity\ProductCategoryInterface;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Entity\ProductSetProductInterface;
use LSB\ProductBundle\Entity\ProductTranslationInterface;
use LSB\ProductBundle\Entity\SupplierInterface;
use LSB\ProductBundle\Entity\SupplierTranslationInterface;
use LSB\ProductBundle\Factory\AssortmentGroupFactory;
use LSB\ProductBundle\Factory\CategoryFactory;
use LSB\ProductBundle\Factory\ProductCategoryFactory;
use LSB\ProductBundle\Factory\ProductFactory;
use LSB\ProductBundle\Factory\ProductSetProductFactory;
use LSB\ProductBundle\Factory\SupplierFactory;
use LSB\ProductBundle\Form\AssortmentGroupTranslationType;
use LSB\ProductBundle\Form\AssortmentGroupType;
use LSB\ProductBundle\Form\CategoryTranslationType;
use LSB\ProductBundle\Form\CategoryType;
use LSB\ProductBundle\Form\ProductCategoryType;
use LSB\ProductBundle\Form\ProductSetProductType;
use LSB\ProductBundle\Form\ProductTranslationType;
use LSB\ProductBundle\Form\SupplierTranslationType;
use LSB\ProductBundle\Form\SupplierType;
use LSB\ProductBundle\LSBProductBundle;
use LSB\ProductBundle\Manager\AssortmentGroupManager;
use LSB\ProductBundle\Manager\CategoryManager;
use LSB\ProductBundle\Manager\ProductCategoryManager;
use LSB\ProductBundle\Manager\ProductManager;
use LSB\ProductBundle\Manager\ProductSetProductManager;
use LSB\ProductBundle\Manager\SupplierManager;
use LSB\ProductBundle\Repository\AssortmentGroupRepository;
use LSB\ProductBundle\Repository\ProductCategoryRepository;
use LSB\ProductBundle\Repository\ProductRepository;
use LSB\ProductBundle\Repository\ProductSetProductRepository;
use LSB\ProductBundle\Repository\SupplierRepository;
use LSB\ProductBundle\Form\ProductType;
use LSB\UtilityBundle\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    const CONFIG_KEY = 'lsb_product';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(self::CONFIG_KEY);

        $treeBuilder
            ->getRootNode()
            ->children()
                ->bundleTranslationDomainScalar(LSBProductBundle::class)->end()
                ->resourcesNode()
                    ->children()
                        ->translatedResourceNode(
                            'product',
                            ProductInterface::class,
                            ProductFactory::class,
                            ProductRepository::class,
                            ProductManager::class,
                            ProductType::class,
                            ProductTranslationInterface::class,
                            ProductTranslationType::class
                        )
                        ->end()
                        ->translatedResourceNode(
                            'assortment_group',
                            AssortmentGroupInterface::class,
                            AssortmentGroupFactory::class,
                            AssortmentGroupRepository::class,
                            AssortmentGroupManager::class,
                            AssortmentGroupType::class,
                            AssortmentGroupTranslationInterface::class,
                            AssortmentGroupTranslationType::class
                        )
                        ->end()
                        ->translatedResourceNode(
                            'supplier',
                            SupplierInterface::class,
                            SupplierFactory::class,
                            SupplierRepository::class,
                            SupplierManager::class,
                            SupplierType::class,
                            SupplierTranslationInterface::class,
                            SupplierTranslationType::class
                        )
                        ->end()
                        ->resourceNode(
                            'product_set_product',
                            ProductSetProductInterface::class,
                            ProductSetProductFactory::class,
                            ProductSetProductRepository::class,
                            ProductSetProductManager::class,
                            ProductSetProductType::class
                        )
                        ->end()
                        ->translatedResourceNode(
                            'supplier',
                            SupplierInterface::class,
                            SupplierFactory::class,
                            SupplierRepository::class,
                            SupplierManager::class,
                            SupplierType::class,
                            SupplierTranslationInterface::class,
                            SupplierTranslationType::class
                        )
                        ->end()
                        ->translatedResourceNode(
                            'category',
                            CategoryInterface::class,
                            CategoryFactory::class,
                            CategoryRepository::class,
                            CategoryManager::class,
                            CategoryType::class,
                            CategoryTranslationInterface::class,
                            CategoryTranslationType::class
                        )
                        ->end()
                        ->resourceNode(
                            'product_category',
                            ProductCategoryInterface::class,
                            ProductCategoryFactory::class,
                            ProductCategoryRepository::class,
                            ProductCategoryManager::class,
                            ProductCategoryType::class
                        )
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
