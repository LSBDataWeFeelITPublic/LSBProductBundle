<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use LSB\UtilityBundle\Interfaces\IdInterface;

interface StorageTranslationInterface extends TranslationInterface, IdInterface
{
    public function getDescription(): ?string;

    public function setDescription(?string $description): self;
}