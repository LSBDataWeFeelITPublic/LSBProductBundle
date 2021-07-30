<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class StorageTranslationType extends BaseEntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'description',
                TextareaType::class
            )
        ;
    }
}