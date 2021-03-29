<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use LSB\UtilityBundle\Traits\UuidTrait;
use LSB\UtilityBundle\Translatable\TranslatableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Supplier
 *
 * Class Supplier
 *
 * @package LSB\ProductBundle\Entity
 * @UniqueEntity("code")
 * @MappedSuperclass
 */
class Supplier implements SupplierInterface, TranslatableInterface
{
    use UuidTrait;
    use CreatedUpdatedTrait;
    use TranslatableTrait;

    /** @var int */
    const TYPE_SELF = 10;

    /** @var int */
    const TYPE_LOCAL = 20;

    /** @var int */
    const TYPE_EXTERNAL = 30;

    /**
     * Name
     *
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * Code name
     *
     * @var string
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Length(max=50, groups={"Default"})
     */
    protected string $code;

    /**
     * Unique number
     *
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $number;

    /**
     * Supplier type
     *
     * @var integer
     * @ORM\Column(type="integer", nullable=false)
     */
    protected int $type = self::TYPE_SELF;

    /**
     * Requires a separate shipment (shipping package)
     *
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected bool $isSeparateShippingPackageRequired = false;

    /**
     * A unique token to confirm data entry by the supplier's panel.
     * It may be regularly refreshed.
     *
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected string $token;

    /**
     * Email address
     *
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $email;

    /**
     * Is default
     *
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected bool $isDefault = false;


    /**
     * Shipping directly by the supplier
     *
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected bool $isShippingBySupplierEnabled = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->generateUuid();
        $this->token = md5(microtime().microtime().rand(1,20));
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
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     * @return $this
     */
    public function setNumber(?string $number): self
    {
        $this->number = $number;
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
    public function isSeparateShippingPackageRequired(): bool
    {
        return $this->isSeparateShippingPackageRequired;
    }

    /**
     * @param bool $isSeparateShippingPackageRequired
     * @return $this
     */
    public function setIsSeparateShippingPackageRequired(bool $isSeparateShippingPackageRequired): self
    {
        $this->isSeparateShippingPackageRequired = $isSeparateShippingPackageRequired;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     * @return $this
     */
    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShippingBySupplierEnabled(): bool
    {
        return $this->isShippingBySupplierEnabled;
    }

    /**
     * @param bool $isShippingBySupplierEnabled
     * @return $this
     */
    public function setIsShippingBySupplierEnabled(bool $isShippingBySupplierEnabled): self
    {
        $this->isShippingBySupplierEnabled = $isShippingBySupplierEnabled;
        return $this;
    }
}
