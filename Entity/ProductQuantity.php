<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping as ORM;
use LSB\UtilityBundle\Helper\ValueHelper;
use LSB\UtilityBundle\Traits\UuidTrait;
use LSB\UtilityBundle\Value\Value;

/**
 * Class ProductSetProduct
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class ProductQuantity implements ProductQuantityInterface
{
    use UuidTrait;

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
    protected int $quantityAvailableAtHand = 0;

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
    public function setStorage(StorageInterface $storage): static
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return int
     */
    public function getQuantity(bool $useValue = true): Value|int
    {
        return $useValue ? ValueHelper::intToValue($this->quantity) : $this->quantity;
    }

    /**
     * @param \LSB\UtilityBundle\Value\Value|int $quantity
     * @return $this
     */
    public function setQuantity(Value|int $quantity): static
    {
        if ($quantity instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($quantity);
            $this->quantity = $amount;
            return $this;
        }

        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return \LSB\UtilityBundle\Value\Value|int
     */
    public function getQuantityAvailableAtHand(bool $useValue = true): Value|int
    {
        return $useValue ? ValueHelper::intToValue($this->quantityAvailableAtHand) : $this->quantityAvailableAtHand;
    }

    /**
     * @param \LSB\UtilityBundle\Value\Value|int $quantityAvailableAtHand
     * @return $this
     */
    public function setQuantityAvailableAtHand(Value|int $quantityAvailableAtHand): static
    {
        if ($quantityAvailableAtHand instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($quantityAvailableAtHand);
            $this->quantityAvailableAtHand = $amount;
            return $this;
        }

        $this->quantityAvailableAtHand = $quantityAvailableAtHand;
        return $this;
    }
}
