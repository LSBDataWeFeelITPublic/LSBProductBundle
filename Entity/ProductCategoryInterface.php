<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use LSB\UtilityBundle\Interfaces\PositionInterface;
use LSB\UtilityBundle\Interfaces\IdInterface;

/**
 * Interface ProductCategoryInterface
 * @package LSB\ProductBundle\Entity
 */
interface ProductCategoryInterface extends IdInterface, PositionInterface
{

}