<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\ProductQuantity;
use LSB\ProductBundle\Entity\Storage;
use LSB\ProductBundle\Entity\StorageInterface;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductQuantityRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductQuantityRepository extends BaseRepository implements ProductQuantityRepositoryInterface
{
    use PaginationRepositoryTrait;

    /**
     * ProductQuantityRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? ProductQuantity::class);
    }

    /**
     * @param int $productId
     * @param bool $useLocalAsRemote
     * @return array
     */
    public function getRemoteDeliveryDaysForProduct(int $productId, bool $useLocalAsRemote = false): array
    {
        $storageTypes = [StorageInterface::TYPE_EXTERNAL];
        if ($useLocalAsRemote) {
            $storageTypes[] = StorageInterface::TYPE_LOCAL;
        }

        $qb = $this->createQueryBuilder('pq');
        $qb
            ->leftJoin('pq.storage', 's')
            ->where('s.type IN (:storageType)')
            ->setParameter('storageType', $storageTypes)
            ->andWhere('IDENTITY(pq.product) = :productId');
        if ($useLocalAsRemote) {
            $qb->andWhere('pq.quantity > 0 OR pq.quantityAvailableAtHand > 0');
        } else {
            $qb->andWhere('pq.quantity > 0');
        }

        $qb->setParameter('productId', $productId)
            ->orderBy('s.deliveryTerm', 'ASC');

        return $qb->getQuery()->execute();
    }

    /**
     * @param int $productId
     * @return int|null
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMinShippingDateFromRemote(int $productId): ?int
    {

        $qb = $this->createQueryBuilder('pq');
        $qb
            ->select('s.deliveryTerm as storageDeliveryTerm')
            ->leftJoin('pq.storage', 's')
            ->where('s.type = :storageType')
            ->andWhere('IDENTITY(pq.product) = :productId')
            ->andWhere('pq.quantity > 0')
            ->orderBy('s.deliveryTerm', 'ASC')
            ->setParameter('storageType', StorageInterface::TYPE_EXTERNAL)
            ->setParameter('productId', $productId)
            ->setMaxResults(1);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $productId
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getRemoteQuantityForProduct($productId): int
    {

        $qb = $this->createQueryBuilder('pq');
        $qb
            ->select('SUM(pq.quantity) as remoteQuantity')
            ->leftJoin('pq.storage', 's')
            ->where('s.type = :storageType')
            ->andWhere('IDENTITY(pq.product) = :productId')
            ->andWhere('pq.quantity > 0')
            ->setParameter('storageType', StorageInterface::TYPE_EXTERNAL)
            ->setParameter('productId', $productId);

        return $qb->getQuery()->getSingleScalarResult();
    }

}
