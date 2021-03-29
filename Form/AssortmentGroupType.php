<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class AssortmentGroupType
 * @package LSB\ProductBundle\Form
 */
class AssortmentGroupType extends BaseEntityType
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