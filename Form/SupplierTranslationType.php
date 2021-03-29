<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SupplierTranslationType
 * @package LSB\ProductBundle\Form
 */
class SupplierTranslationType extends BaseEntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub

        $builder
            ->add(
                'description',
                TextareaType::class
            )
            ->add(
                'productAvailabilityMessage',
                TextareaType::class
            );
    }
}