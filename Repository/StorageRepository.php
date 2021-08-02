<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Storage;
use LSB\ProductBundle\Entity\StorageInterface;
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

    /**
     * @return StorageInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getFirstLocaleStorage(): ?StorageInterface
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s')
            ->where('s.type = :locale')
            ->setParameter('locale', StorageInterface::TYPE_LOCAL)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
