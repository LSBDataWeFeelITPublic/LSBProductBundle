<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CategoryTranslation
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class CategoryTranslation implements CategoryTranslationInterface
{
    use IdTrait;
    use TranslationTrait;
    use SluggableTrait;

    /**
     * Supplier description
     *
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     */
    protected string $name;

    /**
     * Supplier description
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description = null;

    /**
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['name'];
    }

    /**
     * @return bool
     */
    public function shouldGenerateUniqueSlugs(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CategoryTranslation
     */
    public function setName(string $name): CategoryTranslation
    {
        $this->name = $name;
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
     * @return CategoryTranslation
     */
    public function setDescription(?string $description): CategoryTranslation
    {
        $this->description = $description;
        return $this;
    }
}
