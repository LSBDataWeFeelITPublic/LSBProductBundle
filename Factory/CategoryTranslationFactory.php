<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Factory;

use LSB\ProductBundle\Entity\CategoryTranslationInterface;
use LSB\UtilityBundle\Factory\BaseFactory;

/**
 * Class CategoryTranslationFactory
 * @package LSB\ProductBundle\Factory
 */
class CategoryTranslationFactory extends BaseFactory implements CategoryTranslationFactoryInterface
{

    /**
     * @return CategoryTranslationInterface
     */
    public function createNew(): CategoryTranslationInterface
    {
        return parent::createNew();
    }

}
