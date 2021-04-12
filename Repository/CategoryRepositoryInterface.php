<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Repository;

use LSB\UtilityBundle\Repository\NestedTreeRepositoryInterface;
use LSB\UtilityBundle\Repository\PaginationInterface;
use LSB\UtilityBundle\Repository\RepositoryInterface;

/**
 * Interface CategoryRepositoryInterface
 * @package LSB\ProductBundle\Repository
 */
interface CategoryRepositoryInterface extends RepositoryInterface, PaginationInterface, NestedTreeRepositoryInterface
{

}
