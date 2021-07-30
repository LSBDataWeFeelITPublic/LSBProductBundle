<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Storage;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class StorageRepository
 * @package LSB\ProductBundle\Repository
 */
class StorageRepository extends BaseRepository implements StorageRepositoryInterface
{
    use PaginationRepositoryTrait;

    /**
     * StorageRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? Storage::class);
    }

}
