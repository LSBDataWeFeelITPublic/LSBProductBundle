<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Interfaces\UuidInterface;
use LSB\UtilityBundle\Interfaces\NestedTreeInterface;

/**
 * Interface CategoryInterface
 * @package LSB\ProductBundle\Entity
 */
interface CategoryInterface extends UuidInterface, TranslatableInterface, NestedTreeInterface
{

}