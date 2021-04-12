<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Knp\DoctrineBehaviors\ORM\Tree\TreeTrait;
use LSB\ProductBundle\Entity\Category;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class CategoryRepository
 * @package LSB\ProductBundle\Repository
 */
class CategoryRepository extends NestedTreeRepository implements CategoryRepositoryInterface
{
    use PaginationRepositoryTrait;

    public function __construct(EntityManagerInterface $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
    }
}
