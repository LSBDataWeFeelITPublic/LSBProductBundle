<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Interfaces\UuidInterface;

interface StorageInterface extends TranslatableInterface, UuidInterface
{
    const TYPE_SELF = 10;

    const TYPE_LOCAL = 20;

    const TYPE_EXTERNAL = 30;
}