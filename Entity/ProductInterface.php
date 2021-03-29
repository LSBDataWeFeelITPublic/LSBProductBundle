<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Interface ProductInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface ProductInterface
{
    /**
     * @return string|null
     */
    public function getNotes(): ?string;

    /**
     * @param string|null $notes
     * @return $this
     */
    public function setNotes(?string $notes): self;

    /**
     * @return string
     */
    public function getNumber(): string;

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): self;

    /**
     * @return bool
     */
    public function isPackage(): bool;

    /**
     * @param bool $isPackage
     * @return $this
     */
    public function setIsPackage(bool $isPackage): self;

    /**
     * @return float
     */
    public function getItemsInPackage(): float;

    /**
     * @param float $itemsInPackage
     * @return $this
     */
    public function setItemsInPackage(float $itemsInPackage): self;

    /**
     * @return float|null
     */
    public function getWeightNet(): ?float;

    /**
     * @param float|null $weightNet
     * @return $this
     */
    public function setWeightNet(?float $weightNet): self;

    /**
     * @return float|null
     */
    public function getWeightGross(): ?float;

    /**
     * @param float|null $weightGross
     * @return $this
     */
    public function setWeightGross(?float $weightGross): self;

    /**
     * @return string|null
     */
    public function getEanCode(): ?string;

    /**
     * @param string|null $eanCode
     * @return $this
     */
    public function setEanCode(?string $eanCode): self;

    /**
     * @return string|null
     */
    public function getExternalId(): ?string;

    /**
     * @param string|null $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self;

    /**
     * @return int
     */
    public function getPriority(): int;

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority): self;

    /**
     * @return string|null
     */
    public function getUnit(): ?string;

    /**
     * @param string|null $unit
     * @return $this
     */
    public function setUnit(?string $unit): self;

    /**
     * @return mixed
     */
    public function getAttendant();

    /**
     * @param mixed $attendant
     * @return $this
     */
    public function setAttendant($attendant);

    /**
     * @return AssortmentGroupInterface|null
     */
    public function getAssortmentGroup(): ?AssortmentGroupInterface;

    /**
     * @param AssortmentGroupInterface|null $assortmentGroup
     * @return $this
     */
    public function setAssortmentGroup(?AssortmentGroupInterface $assortmentGroup): self;

    /**
     * @return bool
     */
    public function isAvailableForBackorder(): bool;

    /**
     * @param bool $isAvailableForBackorder
     * @return $this
     */
    public function setIsAvailableForBackorder(bool $isAvailableForBackorder): self;

    /**
     * @return bool
     */
    public function isProductSet(): bool;

    /**
     * @param bool $isProductSet
     * @return $this
     */
    public function setIsProductSet(bool $isProductSet): self;

    /**
     * @return ArrayCollection|Collection|ProductSetProductInterface[]
     */
    public function getProductSetProducts();

    /**
     * @param ProductSetProductInterface $productSetProduct
     *
     * @return $this
     */
    public function addProductSetProduct(ProductSetProductInterface $productSetProduct);

    /**
     * @param ProductSetProductInterface $productSetProduct
     *
     * @return $this
     */
    public function removeProductSetProduct(ProductSetProductInterface $productSetProduct);

    /**
     * @param ArrayCollection|Collection|ProductSetProductInterface[] $productSetProducts
     * @return $this
     */
    public function setProductSetProducts($productSetProducts);

    /**
     * @return ArrayCollection|Collection|ProductSetProductInterface[]
     */
    public function getProductSets();

    /**
     * @param ProductSetProductInterface $productSet
     *
     * @return $this
     */
    public function addProductSet(ProductSetProductInterface $productSet);

    /**
     * @param ProductSetProductInterface $productSet
     *
     * @return $this
     */
    public function removeProductSet(ProductSetProductInterface $productSet);

    /**
     * @param ArrayCollection|Collection|ProductSetProductInterface[] $productSets
     * @return $this
     */
    public function setProductSets($productSets);

    /**
     * @return SupplierInterface|null
     */
    public function getSupplier(): ?SupplierInterface;

    /**
     * @param SupplierInterface|null $Supplier
     * @return $this
     */
    public function setSupplier(?SupplierInterface $supplier): self;

    /**
     * @return bool
     */
    public function isUseSupplier(): bool;

    /**
     * @param bool $useSupplier
     * @return $this
     */
    public function setUseSupplier(bool $useSupplier): self;
}