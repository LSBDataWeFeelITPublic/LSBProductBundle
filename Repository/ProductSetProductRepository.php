<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\ProductSetProduct;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class ProductSetProductRepository
 * @package LSB\ProductBundle\Repository
 */
class ProductSetProductRepository extends BaseRepository implements ProductSetProductRepositoryInterface
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

    /**
     * @param array $productSetIds
     * @param bool $returnUuid
     * @return array
     */
    public function getProductSetProductIds(array $productSetIds, bool $returnUuid = false): array
    {
        $formattedResult = [];

        $qb = $this->createQueryBuilder('psp')
            ->leftJoin('psp.product', 'p')
            ->where('psp.productSet IN (:productSetIds)')
            ->setParameter('productSetIds', $productSetIds);

        if ($returnUuid) {
            $qb->select('p.uuid');
        } else {
            $qb->select('p.id');
        }

        $result = $qb
            ->getQuery()
            ->getArrayResult();
        /**
         * @var array $row
         */
        foreach ($result as $row) {
            if ($returnUuid) {
                $formattedResult[] = (string)$row['uuid'];
            } else {
                $formattedResult[] = $row['id'];
            }

        }

        return $formattedResult;
    }
}
