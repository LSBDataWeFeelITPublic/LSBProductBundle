<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TreeNodeInterface;
use LSB\UtilityBundle\Interfaces\UuidInterface;

/**
 * Interface CategoryInterface
 * @package LSB\ProductBundle\Entity
 */
interface CategoryInterface extends UuidInterface, TreeNodeInterface, TranslatableInterface
{

}