<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use LSB\UtilityBundle\Translatable\TranslatableTrait;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\UuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AssortmentGroup
 * @package LSB\ProductBundle\Entity
 * @UniqueEntity(fields={"code"})
 * @MappedSuperclass
 */
class AssortmentGroup implements AssortmentGroupInterface, TranslatableInterface
{
    use UuidTrait;
    use TranslatableTrait;
    use CreatedUpdatedTrait;

    /**
     * Code
     *
     * @var string
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @Assert\Length(max=30, groups={"Default"})
     */
    protected string $code;

    /**
     * Products collection
     *
     * @var ArrayCollection|Collection|ProductInterface[]
     * @ORM\OneToMany(targetEntity="LSB\ProductBundle\Entity\ProductInterface", mappedBy="assortmentGroup", mappedBy="assortmentGroup")
     */
    protected Collection $products;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getCode();
    }

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
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @return string
     */
    public function getCode(): string
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
     * @return ArrayCollection|Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function addProduct(ProductInterface $product): self
    {
        if (false === $this->products->contains($product)) {
            $this->products->add($product);
        }
        return $this;
    }

    /**
     * @param ProductInterface $product
     *
     * @return $this
     */
    public function removeProduct(ProductInterface $product): self
    {
        if (true === $this->products->contains($product)) {
            $this->products->removeElement($product);
        }
        return $this;
    }

    /**
     * @param ArrayCollection|Collection|ProductInterface[] $products
     * @return $this
     */
    public function setProducts($products): self
    {
        $this->products = $products;
        return $this;
    }
}
