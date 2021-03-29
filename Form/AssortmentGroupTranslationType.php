<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProductType
 */
class AssortmentGroupTranslationType extends BaseEntityType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('code', TextType::class);
    }
}