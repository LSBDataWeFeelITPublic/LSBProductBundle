<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TreeNodeInterface;
use Knp\DoctrineBehaviors\Model\Tree\TreeNodeTrait;
use LSB\UtilityBundle\Translatable\TranslatableTrait;
use LSB\UtilityBundle\Traits\CreatedUpdatedTrait;
use LSB\UtilityBundle\Traits\UuidTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category
 * @package LSB\ProductBundle\Entity
 *
 * @MappedSuperclass
 * @Gedmo\Tree(type="nested")
 */
class Category implements CategoryInterface
{
    use UuidTrait;
    use TranslatableTrait;
    use CreatedUpdatedTrait;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    protected ?int $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    protected ?int $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    protected ?int $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\CategoryInterface")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?CategoryInterface $root;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="LSB\ProductBundle\Entity\CategoryInterface", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected ?CategoryInterface $parent;

    /**
     * @ORM\OneToMany(targetEntity="LSB\ProductBundle\Entity\CategoryInterface", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    protected Collection $children;

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
     * @return string
     */
    public function __toString(): string
    {
        return $this->getUuid();
    }

    /**
     * @return int|null
     */
    public function getLft(): ?int
    {
        return $this->lft;
    }

    /**
     * @param int|null $lft
     * @return $this
     */
    public function setLft(?int $lft): self
    {
        $this->lft = $lft;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    /**
     * @param int|null $lvl
     * @return $this
     */
    public function setLvl(?int $lvl): self
    {
        $this->lvl = $lvl;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    /**
     * @param int|null $rgt
     * @return $this
     */
    public function setRgt(?int $rgt): self
    {
        $this->rgt = $rgt;
        return $this;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getRoot(): ?CategoryInterface
    {
        return $this->root;
    }

    /**
     * @param CategoryInterface|null $root
     * @return $this
     */
    public function setRoot(?CategoryInterface $root): self
    {
        $this->root = $root;
        return $this;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getParent(): ?CategoryInterface
    {
        return $this->parent;
    }

    /**
     * @param CategoryInterface|null $parent
     * @return $this
     */
    public function setParent(?CategoryInterface $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param CategoryInterface $children
     *
     * @return $this
     */
    public function addChildren(CategoryInterface $children): self
    {
        if (false === $this->children->contains($children)) {
            $this->children->add($children);
        }
        return $this;
    }

    /**
     * @param CategoryInterface $children
     *
     * @return $this
     */
    public function removeChildren(CategoryInterface $children): self
    {
        if (true === $this->children->contains($children)) {
            $this->children->removeElement($children);
        }
        return $this;
    }

    /**
     * @param Collection $children
     * @return $this
     */
    public function setChildren(Collection $children): self
    {
        $this->children = $children;
        return $this;
    }
}
