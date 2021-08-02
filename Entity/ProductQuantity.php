<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;
use LSB\UtilityBundle\Traits\IdTrait;

/**
 * Class ProductSetProduct
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class ProductQuantity implements ProductQuantityInterface
{
    use IdTrait;

    /**
     * @var ProductInterface
     *
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\ProductInterface")
     */
    protected ProductInterface $product;
    /**
     * @var Storage
     *
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\StorageInterface")
     */
    protected StorageInterface $storage;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected int $quantity = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected int $quantityAvailableAtHand;

    /**
     * @param int $bookQuantity
     * @return $this
     */
    public function bookQuantity(int $bookQuantity): static
    {
        if ($bookQuantity > 0 && $this->quantityAvailableAtHand > 0 && $bookQuantity <= $this->quantityAvailableAtHand) {
            $this->quantityAvailableAtHand -= $bookQuantity;
        } elseif ($bookQuantity > 0 && $this->quantityAvailableAtHand > 0 && $bookQuantity > $this->quantityAvailableAtHand) {
            $this->quantityAvailableAtHand -= $this->quantityAvailableAtHand;
        }

        return $this;
    }

    /**
     * @param int $bookedQuantity
     * @return $this
     */
    public function unbookQuantity(int $bookedQuantity): static
    {
        if ($bookedQuantity > 0 && ($this->quantityAvailableAtHand + $bookedQuantity > $this->quantity)) {
            $this->quantityAvailableAtHand = $this->quantity;
        } elseif ($bookedQuantity > 0) {
            $this->quantityAvailableAtHand += $bookedQuantity;
        }
        return $this;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function setProduct(ProductInterface $product): static
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Storage
     */
    public function getStorage(): Storage|StorageInterface
    {
        return $this->storage;
    }

    /**
     * @param Storage $storage
     * @return $this
     */
    public function setStorage(Storage|StorageInterface $storage): static
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantityAvailableAtHand(): int
    {
        return $this->quantityAvailableAtHand;
    }

    /**
     * @param int $quantityAvailableAtHand
     * @return $this
     */
    public function setQuantityAvailableAtHand(int $quantityAvailableAtHand): static
    {
        $this->quantityAvailableAtHand = $quantityAvailableAtHand;
        return $this;
    }
}
