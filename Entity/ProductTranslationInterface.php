<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

/**
 * Interface ProductTranslationInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface ProductTranslationInterface
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

    /**
     * @return string|null
     */
    public function getAdditionalName(): ?string;

    /**
     * @param string|null $additionalName
     * @return $this
     */
    public function setAdditionalName(?string $additionalName): self;

    /**
     * @return string|null
     */
    public function getSlug(): ?string;

    /**
     * @param string|null $slug
     * @return $this
     */
    public function setSlug(?string $slug): self;

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
    public function getUnit(): ?string;

    /**
     * @param string|null $unit
     * @return $this
     */
    public function setUnit(?string $unit): self;
}