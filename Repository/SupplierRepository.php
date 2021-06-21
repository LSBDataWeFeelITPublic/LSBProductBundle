<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Supplier;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class SupplierRepository
 * @package LSB\ProductBundle\Repository
 */
class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    use PaginationRepositoryTrait;

    /**
     * SupplierRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? Supplier::class);
    }
}
