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
 * Class SupplierTranslation
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class SupplierTranslation implements SupplierTranslationInterface, TranslationInterface
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
     * Text information about the availability of the product from the supplier
     *
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255, groups={"Default"})
     */
    protected ?string $productAvailabilityMessage = null;

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
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProductAvailabilityMessage(): ?string
    {
        return $this->productAvailabilityMessage;
    }

    /**
     * @param string|null $productAvailabilityMessage
     * @return $this
     */
    public function setProductAvailabilityMessage(?string $productAvailabilityMessage): self
    {
        $this->productAvailabilityMessage = $productAvailabilityMessage;
        return $this;
    }
}
