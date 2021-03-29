<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Product;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface, PaginationInterface
{
    use PaginationRepositoryTrait;

    /**
     * ProductRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? Product::class);
    }
}
