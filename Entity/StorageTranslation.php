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
 * Class Storage translation
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class StorageTranslation implements StorageTranslationInterface
{
    use IdTrait;
    use TranslationTrait;

    /**
     * Supplier description
     *
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected ?string $description = null;


    /**
     * SupplierTranslation constructor.
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
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
}
