<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

/**
 * Interface AssortmentGroupTranslationInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface AssortmentGroupTranslationInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self;
}