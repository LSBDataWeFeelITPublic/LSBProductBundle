<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LSB\ProductBundle\Entity\CategoryTranslation;
use LSB\UtilityBundle\Repository\BaseRepository;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\PaginationRepositoryTrait;

/**
 * Class CategoryTranslationRepository
 * @package LSB\ProductBundle\Repository
 */
class CategoryTranslationRepository extends BaseRepository implements CategoryTranslationRepositoryInterface, PaginationInterface
{
    use PaginationRepositoryTrait;

    /**
     * CategoryTranslationRepository constructor.
     * @param ManagerRegistry $registry
     * @param string|null $stringClass
     */
    public function __construct(ManagerRegistry $registry, ?string $stringClass = null)
    {
        parent::__construct($registry, $stringClass ?? CategoryTranslation::class);
    }

}
