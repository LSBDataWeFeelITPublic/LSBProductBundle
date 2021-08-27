<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Interfaces\UuidInterface;


interface StorageInterface extends TranslatableInterface, UuidInterface
{
    const TYPE_LOCAL = 10;
    const TYPE_EXTERNAL = 20;
    const DEFAULT_DELIVERY_TERM = 9;
}