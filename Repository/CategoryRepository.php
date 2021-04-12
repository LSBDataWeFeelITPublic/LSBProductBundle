<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\DoctrineBehaviors\ORM\Tree\TreeTrait;
use LSB\ProductBundle\Entity\Category;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class CategoryRepository
 * @package LSB\ProductBundle\Repository
 */
class CategoryRepository extends ServiceEntityRepository implements CategoryRepositoryInterface
{
    use PaginationRepositoryTrait;
    use TreeTrait;

    /**
     * CategoryRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? Category::class);
    }

}
