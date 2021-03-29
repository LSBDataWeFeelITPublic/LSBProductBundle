<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ProductTranslation
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class ProductTranslation implements ProductTranslationInterface, TranslationInterface
{
    use IdTrait;
    use TranslationTrait;

    /**
     * Product name
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $name = null;

    /**
     * Additional name
     *
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $additionalName = null;

    /**
     * Slug
     *
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Slug(fields={"name", "number"}, updatable=true, separator="-")
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $slug = null;

    /**
     * Product description
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description = null;

    /**
     * Unit (pcs., package or service)
     *
     * @var string|null
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(max=15, groups={"Default"})
     */
    protected ?string $unit = null;

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
    public function getAdditionalName(): ?string
    {
        return $this->additionalName;
    }

    /**
     * @param string|null $additionalName
     * @return $this
     */
    public function setAdditionalName(?string $additionalName): self
    {
        $this->additionalName = $additionalName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return $this
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
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
}
