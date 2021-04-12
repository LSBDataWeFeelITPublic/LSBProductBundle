<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use LSB\ProductBundle\Manager\CategoryManager;
use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Form\EntityLazyType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CategoryType
 * @package LSB\ProductBundle\Form
 */
class CategoryType extends BaseEntityType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('parentNode',
                EntityLazyType::class,
                [
                    'class' => $options['data_class']]
            )
//            ->add(
//                'translations',
//                TranslationsType::class
//            )
        ;
    }
}
