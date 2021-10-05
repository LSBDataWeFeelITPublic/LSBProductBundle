<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Symfony\Component\Validator\Constraints\Length;

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
                [
                    'documentation' => [
                        'maxLength' => 50,
                        'description' => 'EAN code'
                    ]
                ]
            )
            ->add(
                'number',
                TextType::class,
                [
                    'documentation' => [
                        'maxLength' => 50
                    ]
                ]
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

//        ->add(
//        'translations',
//        CollectionType::class,
//        [
//            'entry_type' => ProductTranslationType::class,
//            'allow_add' => true,
//            'allow_delete' => true
//        ]
//    );
    }
}