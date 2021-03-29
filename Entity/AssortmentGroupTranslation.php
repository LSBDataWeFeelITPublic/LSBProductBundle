<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use LSB\UtilityBundle\Traits\IdTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AssortmentGroupTranslation
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class AssortmentGroupTranslation implements AssortmentGroupTranslationInterface, TranslationInterface
{
    use IdTrait;
    use TranslationTrait;

    /**
     * Name
     *
     * @var string|null
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $name = null;

    /**
     * AssortmentGroupTranslation constructor.
     * @throws \Exception
     */
    public function __construct()
    {

    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @throws \Exception
     */
    public function __clone()
    {

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
}
