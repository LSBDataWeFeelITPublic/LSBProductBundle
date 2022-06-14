<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Helper\ValueHelper;
use LSB\UtilityBundle\Translatable\TranslatableTrait;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\UuidTrait;
use LSB\UtilityBundle\Value\Value;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 * @package LSB\ProductBundle\Entity
 * @UniqueEntity("number")
 * @MappedSuperclass
 */
class Product implements ProductInterface
{
    use UuidTrait;
    use TranslatableTrait;
    use CreatedUpdatedTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"default": 10})
     */
    protected int $type = self::TYPE_DEFAULT;

    /**
     * Stuff notes
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $notes = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=50, nullable=false, unique=true)
     * @Assert\Length(max=50, groups={"Default"})
     */
    protected ?string $number = null;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected bool $isPackage = false;

    /**
     * TODO use Value
     *
     * @var float|null
     * @ORM\Column(type="decimal", precision=18, scale=1, nullable=true)
     */
    protected ?float $itemsInPackage = 1.0;

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
     * List of product quantity records
     *
     * @var ArrayCollection|Collection|ProductQuantityInterface[]
     *
     * @ORM\OneToMany(targetEntity="LSB\ProductBundle\Entity\ProductQuantityInterface", mappedBy="product", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"storage" = "ASC"})
     */
    protected Collection $productQuantities;

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
     * @var bool
     * @ORM\Column(type="boolean", nullable=false, options={"default": true})
     */
    protected bool $isEnabled = true;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $quantity = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $quantityAvailableAtHand = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $localQuantity = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $localQuantityAvailableAtHand = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $externalQuantity = null;

    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $externalQuantityAvailableAtHand = null;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTime $availableFromDate = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->generateUuid();
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
     * TODO
     * @param string|null $storageNumber
     * @return int
     */
    public function getShippingDays(?string $storageNumber): int
    {
        return 1;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->uuid;
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
     * @return float|null
     */
    public function getItemsInPackage(): ?float
    {
        return $this->itemsInPackage ? (float) $this->itemsInPackage : null;
    }

    /**
     * @param float|null $itemsInPackage
     * @return $this
     */
    public function setItemsInPackage(?float $itemsInPackage): self
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
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection|\LSB\ProductBundle\Entity\ProductQuantityInterface[]
     */
    public function getProductQuantities(): ArrayCollection|Collection|array
    {
        return $this->productQuantities;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection|\LSB\ProductBundle\Entity\ProductQuantityInterface[] $productQuantities
     * @return Product
     */
    public function setProductQuantities(ArrayCollection|Collection|array $productQuantities): Product
    {
        $this->productQuantities = $productQuantities;
        return $this;
    }

    /**
     * @param ProductSetProductInterface $productSet
     *
     * @return $this
     */
    public function addProductQuantity(ProductQuantityInterface $productQuantity)
    {
        if (false === $this->productQuantities->contains($productQuantity)) {
            $productQuantity->setProduct($this);
            $this->productQuantities->add($productQuantity);
        }
        return $this;
    }

    /**
     * @param ProductQuantityInterface $productQuantity
     *
     * @return $this
     */
    public function removeProductQuantity(ProductQuantityInterface $productQuantity)
    {
        if (true === $this->productQuantities->contains($productQuantity)) {
            $this->productQuantities->removeElement($productQuantity);
        }
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

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int|null
     */
    public function getLocalQuantity(bool $useValue = false): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->localQuantity, $this->unit) : $this->localQuantity;
    }

    /**
     * @param Value|int|null $localQuantity
     * @return Product
     */
    public function setLocalQuantity(Value|int|null $localQuantity): static
    {
        if ($localQuantity instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($localQuantity);
            $this->localQuantity = $amount;
            return $this;
        }

        $this->localQuantity = $localQuantity;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int|null
     */
    public function getLocalQuantityAvailableAtHand(bool $useValue = false): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->localQuantityAvailableAtHand, $this->unit) : $this->localQuantityAvailableAtHand;
    }

    /**
     * @param Value|int|null $localQuantityAvailableAtHand
     * @return Product
     */
    public function setLocalQuantityAvailableAtHand(Value|int|null $localQuantityAvailableAtHand): static
    {
        if ($localQuantityAvailableAtHand instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($localQuantityAvailableAtHand);
            $this->localQuantityAvailableAtHand = $amount;
            return $this;
        }

        $this->localQuantityAvailableAtHand = $localQuantityAvailableAtHand;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int|null
     */
    public function getExternalQuantity(bool $useValue = false): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->externalQuantity, $this->unit) : $this->localQuantityAvailableAtHand;
    }

    /**
     * @param Value|int|null $externalQuantity
     * @return Product
     */
    public function setExternalQuantity(Value|int|null $externalQuantity): static
    {
        if ($externalQuantity instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($externalQuantity);
            $this->externalQuantity = $amount;
            return $this;
        }

        $this->externalQuantity = $externalQuantity;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int
     */
    public function getExternalQuantityAvailableAtHand(bool $useValue = true): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->externalQuantityAvailableAtHand, $this->unit) : $this->externalQuantityAvailableAtHand;
    }

    /**
     * @param Value|int|null $externalQuantityAvailableAtHand
     * @return Product
     */
    public function setExternalQuantityAvailableAtHand(Value|int|null $externalQuantityAvailableAtHand): static
    {
        if ($externalQuantityAvailableAtHand instanceof Value)
        {
            [$amount, $unit] = ValueHelper::valueToIntUnit($externalQuantityAvailableAtHand);
            $this->externalQuantity = $amount;
            return $this;
        }

        $this->externalQuantityAvailableAtHand = $externalQuantityAvailableAtHand;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getAvailableFromDate(): ?DateTime
    {
        return $this->availableFromDate;
    }

    /**
     * @param DateTime|null $availableFromDate
     * @return Product
     */
    public function setAvailableFromDate(?DateTime $availableFromDate): Product
    {
        $this->availableFromDate = $availableFromDate;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int|null
     */
    public function getQuantity(bool $useValue = false): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->quantity, $this->unit) : $this->quantity;
    }

    /**
     * @param Value|int|null $quantity
     * @return Product
     */
    public function setQuantity(Value|int|null $quantity): Product
    {
        if ($quantity instanceof Value) {
            [$amount, $unit] = ValueHelper::valueToIntUnit($quantity);
            $this->quantity = $amount;
            return $this;
        }

        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param bool $useValue
     * @return Value|int|null
     */
    public function getQuantityAvailableAtHand(bool $useValue = false): Value|int|null
    {
        return $useValue ? ValueHelper::intToValue($this->quantityAvailableAtHand, $this->unit) : $this->quantityAvailableAtHand;
    }

    /**
     * @param Value|int|null $quantityAvailableAtHand
     * @return Product
     */
    public function setQuantityAvailableAtHand(Value|int|null $quantityAvailableAtHand): Product
    {
        if ($quantityAvailableAtHand instanceof Value) {
            [$amount, $unit] = ValueHelper::valueToIntUnit($quantityAvailableAtHand);
            $this->quantityAvailableAtHand = $amount;
            return $this;
        }

        $this->quantityAvailableAtHand = $quantityAvailableAtHand;
        return $this;
    }
}
