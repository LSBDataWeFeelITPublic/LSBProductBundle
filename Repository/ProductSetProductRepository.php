<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Product;
use LSB\ProductBundle\Entity\ProductSetProduct;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductSetProductRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductSetProductRepository extends ServiceEntityRepository implements ProductSetProductRepositoryInterface, PaginationInterface
{
    use PaginationRepositoryTrait;

    /**
     * ProductSetProductRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? ProductSetProduct::class);
    }
}
