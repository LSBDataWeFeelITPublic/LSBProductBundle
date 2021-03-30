<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use Doctrine\ORM\Mapping\MappedSuperclass;

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
     * @var ProductInterface
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\ProductInterface", inversedBy="productSetProducts")
     * @Gedmo\SortableGroup
     */
    protected ?ProductInterface $productSet = null;

    /**
     * Position
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\SortablePosition
     */
    protected ?int $position = 0;

    /**
     * Component product
     *
     * @var float|null
     * @ORM\Column(type="decimal", precision=18, scale=1, nullable=false, options={"default": 1})
     */
    protected ?float $quantity = 1;

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
     * @return float|null
     */
    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    /**
     * @param float|null $quantity
     * @return $this
     */
    public function setQuantity(?float $quantity): self
    {
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
}
