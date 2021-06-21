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
}
