<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\Product;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
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

    /**
     * @return ProductInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByNumber(): ?ProductInterface
    {
        $qb = $this->createQueryBuilder('p')
            ->where('lower(p.number) LIKE lower(:number)');

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $productSetUuid
     * @param string $productUuid
     * @return Product|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getProductSetByProductAndUuid(string $productSetUuid, string $productUuid): ?ProductInterface
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.uuid = :productSetUuid')
            ->andWhere('p.isProductSet = TRUE')
            ->leftJoin('p.productSetProducts', 'psp')
            ->leftJoin('psp.product', 'pspp')
            ->andWhere('pspp.uuid = :productUuid')
            ->setParameter('productSetUuid', $productSetUuid)
            ->setParameter('productUuid', $productUuid)
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param array $productDetailsUuids
     * @return array
     */
    public function checkEnabledProductUuids(array $productDetailsUuids): array
    {
        $formattedResult = [];

        $qb = $this->createQueryBuilder('p')
            ->select('DISTINCT(p.uuid) as uuid')
            ->where('pd.isEnabled = true')
            ->where('p.uuid IN (:productDetailsUuids)')
            ->setParameter('productDetailsUuids', array_values($productDetailsUuids));


        $result = $qb->getQuery()->execute();

        if (array_key_exists(0, $result)) {
            foreach ($result as $row) {
                $formattedResult[] = $row['uuid'];
            }
        }

        return $formattedResult;
    }

    /**
     * @param array $productSetUuids
     * @return array
     */
    public function getProductSetsIdsByProductUuids(array $productSetUuids): array
    {
        $result = $qb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->where('p.uuid IN (:productSetIds)')
            ->andWhere('p.isProductSet = TRUE')
            ->setParameter(':productSetIds', $productSetUuids)
            ->getQuery()
            ->getArrayResult();

        return (array_column($result, "id"));
    }
}
