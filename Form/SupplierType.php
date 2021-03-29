<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class ProductType
 */
class SupplierType extends BaseEntityType
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
                'name',
                TextType::class
            )
            ->add(
                'code',
                TextType::class
            )
            ->add(
                'number',
                TextType::class
            )
            ->add(
                'type',
                IntegerType::class
            )
            ->add(
                'isSeparateShippingPackageRequired',
                CheckboxType::class
            )
            ->add(
                'isSeparateShippingPackageRequired',
                EmailType::class
            )
            ->add(
                'isDefault',
                CheckboxType::class
            )
            ->add(
                'isShippingBySupplierEnabled',
                CheckboxType::class
            )
        ;
    }
}