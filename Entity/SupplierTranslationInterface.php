<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

/**
 * Interface SupplierTranslationInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface SupplierTranslationInterface
{
    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self;

    /**
     * @return string|null
     */
    public function getProductAvailabilityMessage(): ?string;

    /**
     * @param string|null $productAvailabilityMessage
     * @return $this
     */
    public function setProductAvailabilityMessage(?string $productAvailabilityMessage): self;
}