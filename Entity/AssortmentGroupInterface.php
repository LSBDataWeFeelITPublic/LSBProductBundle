<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Interfaces\UuidInterface;

/**
 * Interface AssortmentGroupInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface AssortmentGroupInterface extends TranslatableInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self;

    /**
     * @return ArrayCollection|Collection|Product[]
     */
    public function getProducts(): Collection;

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function addProduct(ProductInterface $product): self;

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function removeProduct(ProductInterface $product): self;

    /**
     * @param ArrayCollection|Collection|ProductInterface[] $products
     * @return $this
     */
    public function setProducts($products): self;
}