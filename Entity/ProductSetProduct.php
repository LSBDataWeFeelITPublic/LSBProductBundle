<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use LSB\UtilityBundle\Helper\ValueHelper;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use Doctrine\ORM\Mapping\MappedSuperclass;
use LSB\UtilityBundle\Value\Value;

/**
 * Class ProductSetProduct
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class ProductSetProduct implements ProductSetProductInterface
{
    use IdTrait;
    use CreatedUpdatedTrait;

    /**
     * Component product of the set
     *
     * @var ProductInterface|null
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\ProductInterface", inversedBy="productSets")
     */
    protected ?ProductInterface $product = null;

    /**
     * Product with product set flag (product set)
     *
     * @var ProductInterface|null
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\ProductInterface", inversedBy="productSetProducts")
     * @Gedmo\SortableGroup
     */
    protected ?ProductInterface $productSet = null;

    /**
     * Position
     *
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\SortablePosition
     */
    protected ?int $position = 0;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=false, options={"default": 1})
     */
    protected ?int $quantity = 1;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected ?string $unit = null;

    /**
     * Is default product
     *
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected bool $isMain = false;

    /**
     * ProductSetProduct constructor.
     * @throws \Exception
     */
    public function __construct()
    {

    }

    /**
     * @throws \Exception
     */
    public function __clone()
    {
        $this->id = null;

    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface|null $product
     * @return $this
     */
    public function setProduct(?ProductInterface $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProductSet(): ?ProductInterface
    {
        return $this->productSet;
    }

    /**
     * @param ProductInterface $productSet
     * @return $this
     */
    public function setProductSet(ProductInterface $productSet): self
    {
        $this->productSet = $productSet;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int
     */
    public function getQuantity(bool $useValue = false): Value|int
    {
        return $useValue ? ValueHelper::intToValue($this->quantity, $this->unit) : $this->quantity;
    }

    /**
     * @param Value|int $quantity
     * @return $this
     */
    public function setQuantity(Value|int $quantity): self
    {
        if ($quantity instanceof Value) {
            $this->quantity = (int) $quantity->getAmount();
            $this->unit = $quantity->getUnit();
        }

        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->isMain;
    }

    /**
     * @param bool $isMain
     * @return $this
     */
    public function setIsMain(bool $isMain): self
    {
        $this->isMain = $isMain;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @param string|null $unit
     * @return ProductSetProduct
     */
    public function setUnit(?string $unit): ProductSetProduct
    {
        $this->unit = $unit;
        return $this;
    }
}
