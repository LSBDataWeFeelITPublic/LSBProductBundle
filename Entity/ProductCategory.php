<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use LSB\UtilityBundle\Traits\IdTrait;
use LSB\UtilityBundle\Traits\PositionTrait;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;

/**
 * Class ProductCategory
 * @package LSB\ProductBundle\Entity
 * @MappedSuperclass
 */
class ProductCategory implements ProductCategoryInterface
{
    use IdTrait;
    use CreatedUpdatedTrait;
    use PositionTrait;

    /**
     * Component product of the set
     *
     * @var ProductInterface
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\ProductInterface", inversedBy="productCategories")
     */
    protected ProductInterface $product;

    /**
     * Product with product set flag (product set)
     *
     * @var CategoryInterface
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\CategoryInterface", inversedBy="productCategories")
     */
    protected CategoryInterface $category;

    /**
     * @return ProductInterface
     */
    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     * @return $this
     */
    public function setProduct(ProductInterface $product): self
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return CategoryInterface
     */
    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    /**
     * @param CategoryInterface $category
     * @return $this
     */
    public function setCategory(CategoryInterface $category): self
    {
        $this->category = $category;
        return $this;
    }



}
