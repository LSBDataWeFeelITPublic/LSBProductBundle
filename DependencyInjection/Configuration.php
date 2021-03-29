<?php
declare(strict_types=1);

namespace LSB\ProductBundle\DependencyInjection;

use LSB\ProductBundle\Entity\AssortmentGroupInterface;
use LSB\ProductBundle\Entity\AssortmentGroupTranslationInterface;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Entity\ProductSetProductInterface;
use LSB\ProductBundle\Entity\ProductTranslationInterface;
use LSB\ProductBundle\Entity\SupplierInterface;
use LSB\ProductBundle\Entity\SupplierTranslationInterface;
use LSB\ProductBundle\Factory\AssortmentGroupFactory;
use LSB\ProductBundle\Factory\ProductFactory;
use LSB\ProductBundle\Factory\ProductSetProductFactory;
use LSB\ProductBundle\Factory\SupplierFactory;
use LSB\ProductBundle\Form\AssortmentGroupTranslationType;
use LSB\ProductBundle\Form\AssortmentGroupType;
use LSB\ProductBundle\Form\ProductSetProductType;
use LSB\ProductBundle\Form\ProductTranslationType;
use LSB\ProductBundle\Form\SupplierTranslationType;
use LSB\ProductBundle\Form\SupplierType;
use LSB\ProductBundle\LSBProductBundle;
use LSB\ProductBundle\Manager\AssortmentGroupManager;
use LSB\ProductBundle\Manager\ProductManager;
use LSB\ProductBundle\Manager\ProductSetProductManager;
use LSB\ProductBundle\Manager\SupplierManager;
use LSB\ProductBundle\Repository\AssortmentGroupRepository;
use LSB\ProductBundle\Repository\ProductRepository;
use LSB\ProductBundle\Repository\ProductSetProductRepository;
use LSB\ProductBundle\Repository\SupplierRepository;
use LSB\ProductBundle\Form\ProductType;
use LSB\UtilityBundle\DependencyInjection\BaseExtension as BE;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
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
                ->scalarNode(BE::CONFIG_KEY_TRANSLATION_DOMAIN)->defaultValue((new \ReflectionClass(LSBProductBundle::class))->getShortName())->end()
                ->arrayNode(BE::CONFIG_KEY_RESOURCES)
                    ->children()
                        // Start Product
                        ->arrayNode('product')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_CLASSES)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(ProductInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->defaultValue(ProductFactory::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->defaultValue(ProductRepository::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_MANAGER)->defaultValue(ProductManager::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(ProductType::class)->end()
                                ->end()
                                ->end()
                            ->end()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_TRANSLATION)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(ProductTranslationInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(ProductTranslationType::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        // End Product
                        // Start AssortmentGroup
                        ->arrayNode('assortment_group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_CLASSES)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(AssortmentGroupInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->defaultValue(AssortmentGroupFactory::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->defaultValue(AssortmentGroupRepository::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_MANAGER)->defaultValue(AssortmentGroupManager::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(AssortmentGroupType::class)->end()
                                ->end()
                                ->end()
                            ->end()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_TRANSLATION)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(AssortmentGroupTranslationInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(AssortmentGroupTranslationType::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        // End AssortmentGroup
                        // Start Supplier
                        ->arrayNode('supplier')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_CLASSES)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(SupplierInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->defaultValue(SupplierFactory::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->defaultValue(SupplierRepository::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_MANAGER)->defaultValue(SupplierManager::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(SupplierType::class)->end()
                                ->end()
                                ->end()
                            ->end()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_TRANSLATION)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(SupplierTranslationInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(SupplierTranslationType::class)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        // End Supplier
                        // Start ProductSetProduct
                        ->arrayNode('product_set_product')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode(BE::CONFIG_KEY_CLASSES)
                                ->children()
                                    ->scalarNode(BE::CONFIG_KEY_ENTITY)->end()
                                    ->scalarNode(BE::CONFIG_KEY_INTERFACE)->defaultValue(ProductSetProductInterface::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FACTORY)->defaultValue(ProductSetProductFactory::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_REPOSITORY)->defaultValue(ProductSetProductRepository::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_MANAGER)->defaultValue(ProductSetProductManager::class)->end()
                                    ->scalarNode(BE::CONFIG_KEY_FORM)->defaultValue(ProductSetProductType::class)->end()
                                ->end()
                                ->end()
                            ->end()
                        ->end()
                        // End ProductSetProduct
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
