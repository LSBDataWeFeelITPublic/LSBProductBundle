<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;

/**
 * Class ProductType
 * @package LSB\ProductBundle\Form
 */
class ProductType extends BaseEntityType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'eanCode',
                TextType::class,
            )
            ->add(
                'number',
                TextType::class
            )
            ->add('isPackage',
                CheckboxType::class
            )
            ->add('itemsInPackage',
                NumberType::class
            )
            ->add('priority',
                NumberType::class
            )
            ->add('isProductSet',
                CheckboxType::class
            )
            ->add('useSupplier',
                CheckboxType::class
            )
            ->add(
                'translations',
                TranslationsType::class
            );
    }
}