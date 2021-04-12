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

    const CHILDREN_KEY = '__children';
    const API_RESOURCES_KEY = 'resources';

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

    /**
     * @param array $nodes
     * @param callable $callback
     * @param array $options
     */
    protected function walkNode(array &$nodes, callable $callback, array $options = []): void
    {
        $childrenKey = $options['childrenKey'] ?? self::CHILDREN_KEY;

        foreach ($nodes as $key => $value) {
            call_user_func_array($callback, [&$value]);

            if (array_key_exists($childrenKey, $value) && $value[$childrenKey]) {
                $children = $value[$childrenKey];
                $this->walkNode($children, $callback, $options);
            }
        }
    }

    /**
     * @param array $nodes
     * @param callable $callback
     * @param array $options
     */
    protected function updateNode(array &$nodes, callable $callback, array $options = []): void
    {
        $childrenKey = $options['childrenKey'] ?? self::CHILDREN_KEY;

        foreach ($nodes as $key => $value) {
            call_user_func_array($callback, [&$value]);

            if (array_key_exists($childrenKey, $value) && $value[$childrenKey]) {
                $children = $value[$childrenKey];
                $this->updateNode($children, $callback, $options);

                $value[$childrenKey] = $children;
            }

            $nodes[$key] = $value;
        }
    }

    /**
     * @param CategoryInterface|null $category
     * @param array $resorted
     * @return array
     */
    public function getHierarchy(?CategoryInterface $category = null, array $options = [], array $resources = []): array
    {
        $tree = $this->getRepository()->childrenHierarchy($category);

        $resourceKey = $options['resourceKey'] ?? self::API_RESOURCES_KEY;

        $this->updateNode($tree, function (array &$row) use (&$resources, $resourceKey) {
            $id = array_key_exists('id', $row) && $row['id'] ? $row['id'] : null;
            $row[$resourceKey] = [];

            if (array_key_exists($id, $resources) && count($resources[$id])) {
                $row['resources'] = $resources[$id];
            }

            if (array_key_exists('translations', $row)) {
                foreach ($row['translations'] as $countryIsoCode => $translation) {
                    unset($row['translations'][$countryIsoCode]['id']);
                }
            }

            $row['uuid'] = (string) $row['uuid'];

            /**
             * @var string $key
             * @var mixed $value
             */
            foreach ($row as $key => $value) {
                if (preg_match("/\_(id)$/", $key)) {
                    unset($row[$key]);
                }
            }

            unset($row['id']);
        });

        return $tree;
    }
}
