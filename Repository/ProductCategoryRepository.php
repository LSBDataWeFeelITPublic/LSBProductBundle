<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\ProductCategory;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductCategoryRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductCategoryRepository extends BaseRepository implements ProductCategoryRepositoryInterface
{
    use PaginationRepositoryTrait;

    /**
     * ProductCategoryRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? ProductCategory::class);
    }

}
