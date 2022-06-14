<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use Doctrine\Common\Collections\ArrayCollection;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class ProductFactory
 * @package LSB\ProductBundle\Factory
 */
class ProductFactory extends BaseFactory implements ProductFactoryInterface
{
    /**
     * @return ProductInterface
     */
    public function createNew(): ProductInterface
    {
        /**
         * @var ProductInterface $object
         */
        $object = parent::createNew();

        $object
            ->setProductSetProducts(new ArrayCollection)
            ->setProductQuantities(new ArrayCollection)
        ;

        return $object;
    }
}