<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use LSB\ProductBundle\Entity\AssortmentGroup;
use Doctrine\Persistence\ManagerRegistry;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class AssortmentGroupRepository
 * @package LSB\ProductBundle\Repository
 */
class AssortmentGroupRepository extends BaseRepository implements AssortmentGroupRepositoryInterface
{
    use PaginationRepositoryTrait;

    /**
     * AssortmentGroupRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? AssortmentGroup::class);
    }
}
