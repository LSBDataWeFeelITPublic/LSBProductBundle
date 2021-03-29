<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Translatable\TranslatableTrait;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\UuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package LSB\ProductBundle\Entity
 * @UniqueEntity("number")
 * @MappedSuperclass
 */
class Product implements ProductInterface, TranslatableInterface
{
    use UuidTrait;
    use TranslatableTrait;
    use CreatedUpdatedTrait;

    /**
     * Stuff notes
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $notes = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     * @Assert\Length(max=50, groups={"Default"})
     */
    protected string $number;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected bool $isPackage = false;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=1, nullable=false)
     */
    protected float $itemsInPackage = 1.0;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=18, scale=2, nullable=true)
     */
    protected ?float $weightNet = null;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=18, scale=2, nullable=true)
     */
    protected ?float $weightGross = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(max="50", groups={"Default"})
     */
    protected ?string $eanCode = null;

    /**
     * Product ID, UUID in external system (ERP, CRM)
     *
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $externalId = null;

    /**
     * Product priority, 1 - most important, least important
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false, options={"default": 255})
     */
    protected int $priority = 255;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(max=15, groups={"Default"})
     */
    protected ?string $unit = null;

    /**
     * Sales person, product attendant (Name or code)
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $attendant = null;

    /**
     * @var AssortmentGroupInterface|null
     *
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\AssortmentGroupInterface", inversedBy="products", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected ?AssortmentGroupInterface $assortmentGroup = null;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable = false, options={"default": true})
     */
    protected bool $isAvailableForBackorder = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected bool $isProductSet = false;

    /**
     * List of components of the set
     *
     * @var ArrayCollection|Collection|ProductSetProductInterface[]
     *
     * @ORM\OneToMany(targetEntity="LSB\ProductBundle\Entity\ProductSetProductInterface", mappedBy="productSet", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected Collection $productSetProducts;

    /**
     * Collection of sets to which the product belongs
     *
     * @var ArrayCollection|Collection|ProductSetProductInterface[]
     * @ORM\OneToMany(targetEntity="LSB\ProductBundle\Entity\ProductSetProductInterface", mappedBy="product", cascade={"persist"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected Collection $productSets;

    /**
     * Supplier
     *
     * @var SupplierInterface|null
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\SupplierInterface", fetch="EAGER")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected ?SupplierInterface $supplier = null;

    /**
     * Enables supplier functionality for the product
     *
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected bool $useSupplier = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->generateUuid();
        $this->products = new ArrayCollection();
    }

    /**
     * @throws \Exception
     */
    public function __clone()
    {
        $this->id = null;
        $this->generateUuid(true);
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->number;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string|null $notes
     * @return $this
     */
    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPackage(): bool
    {
        return $this->isPackage;
    }

    /**
     * @param bool $isPackage
     * @return $this
     */
    public function setIsPackage(bool $isPackage): self
    {
        $this->isPackage = $isPackage;
        return $this;
    }

    /**
     * @return float
     */
    public function getItemsInPackage(): float
    {
        return (float) $this->itemsInPackage;
    }

    /**
     * @param float $itemsInPackage
     * @return $this
     */
    public function setItemsInPackage(float $itemsInPackage): self
    {
        $this->itemsInPackage = $itemsInPackage;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeightNet(): ?float
    {
        return $this->weightNet ? (float) $this->getWeightNet() : null;
    }

    /**
     * @param float|null $weightNet
     * @return $this
     */
    public function setWeightNet(?float $weightNet): self
    {
        $this->weightNet = $weightNet;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getWeightGross(): ?float
    {
        return $this->weightGross ? (float) $this->weightGross : null;
    }

    /**
     * @param float|null $weightGross
     * @return $this
     */
    public function setWeightGross(?float $weightGross): self
    {
        $this->weightGross = $weightGross;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEanCode(): ?string
    {
        return $this->eanCode;
    }

    /**
     * @param string|null $eanCode
     * @return $this
     */
    public function setEanCode(?string $eanCode): self
    {
        $this->eanCode = $eanCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     * @return $this
     */
    public function setExternalId(?string $externalId): self
    {
        $this->externalId = $externalId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;
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
     * @return $this
     */
    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttendant()
    {
        return $this->attendant;
    }

    /**
     * @param mixed $attendant
     * @return $this
     */
    public function setAttendant($attendant)
    {
        $this->attendant = $attendant;
        return $this;
    }

    /**
     * @return AssortmentGroupInterface|null
     */
    public function getAssortmentGroup(): ?AssortmentGroupInterface
    {
        return $this->assortmentGroup;
    }

    /**
     * @param AssortmentGroupInterface|null $assortmentGroup
     * @return $this
     */
    public function setAssortmentGroup(?AssortmentGroupInterface $assortmentGroup): self
    {
        $this->assortmentGroup = $assortmentGroup;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailableForBackorder(): bool
    {
        return $this->isAvailableForBackorder;
    }

    /**
     * @param bool $isAvailableForBackorder
     * @return $this
     */
    public function setIsAvailableForBackorder(bool $isAvailableForBackorder): self
    {
        $this->isAvailableForBackorder = $isAvailableForBackorder;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProductSet(): bool
    {
        return $this->isProductSet;
    }

    /**
     * @param bool $isProductSet
     * @return $this
     */
    public function setIsProductSet(bool $isProductSet): self
    {
        $this->isProductSet = $isProductSet;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection|ProductSetProductInterface[]
     */
    public function getProductSetProducts()
    {
        return $this->productSetProducts;
    }

    /**
     * @param ProductSetProductInterface $productSetProduct
     *
     * @return $this
     */
    public function addProductSetProduct(ProductSetProductInterface $productSetProduct)
    {
        if (false === $this->productSetProducts->contains($productSetProduct)) {
            $this->productSetProducts->add($productSetProduct);
        }
        return $this;
    }

    /**
     * @param ProductSetProductInterface $productSetProduct
     *
     * @return $this
     */
    public function removeProductSetProduct(ProductSetProductInterface $productSetProduct)
    {
        if (true === $this->productSetProducts->contains($productSetProduct)) {
            $this->productSetProducts->removeElement($productSetProduct);
        }
        return $this;
    }

    /**
     * @param ArrayCollection|Collection|ProductSetProductInterface[] $productSetProducts
     * @return $this
     */
    public function setProductSetProducts($productSetProducts)
    {
        $this->productSetProducts = $productSetProducts;
        return $this;
    }

    /**
     * @return ArrayCollection|Collection|ProductSetProductInterface[]
     */
    public function getProductSets()
    {
        return $this->productSets;
    }

    /**
     * @param ProductSetProductInterface $productSet
     *
     * @return $this
     */
    public function addProductSet(ProductSetProductInterface $productSet)
    {
        if (false === $this->productSets->contains($productSet)) {
            $this->productSets->add($productSet);
        }
        return $this;
    }

    /**
     * @param ProductSetProductInterface $productSet
     *
     * @return $this
     */
    public function removeProductSet(ProductSetProductInterface $productSet)
    {
        if (true === $this->productSets->contains($productSet)) {
            $this->productSets->removeElement($productSet);
        }
        return $this;
    }

    /**
     * @param ArrayCollection|Collection|ProductSetProductInterface[] $productSets
     * @return $this
     */
    public function setProductSets($productSets)
    {
        $this->productSets = $productSets;
        return $this;
    }

    /**
     * @return SupplierInterface|null
     */
    public function getSupplier(): ?SupplierInterface
    {
        return $this->supplier;
    }

    /**
     * @param SupplierInterface|null $Supplier
     * @return $this
     */
    public function setSupplier(?SupplierInterface $supplier): self
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @return bool
     */
    public function isUseSupplier(): bool
    {
        return $this->useSupplier;
    }

    /**
     * @param bool $useSupplier
     * @return $this
     */
    public function setUseSupplier(bool $useSupplier): self
    {
        $this->useSupplier = $useSupplier;
        return $this;
    }
}
