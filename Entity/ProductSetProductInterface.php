<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

/**
 * Interface ProductSetProductInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface ProductSetProductInterface
{
    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @param ProductInterface|null $product
     * @return $this
     */
    public function setProduct(?ProductInterface $product): self;

    /**
     * @return ProductInterface|null
     */
    public function getProductSet(): ?ProductInterface;

    /**
     * @param ProductInterface $productSet
     * @return $this
     */
    public function setProductSet(ProductInterface $productSet): self;

    /**
     * @return int
     */
    public function getPosition(): int;

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): self;

    /**
     * @return float|null
     */
    public function getQuantity(): ?float;

    /**
     * @param float|null $quantity
     * @return $this
     */
    public function setQuantity(?float $quantity): self;

    /**
     * @return bool
     */
    public function isMain(): bool;

    /**
     * @param bool $isMain
     * @return $this
     */
    public function setIsMain(bool $isMain): self;
}