<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Form;

use LSB\UtilityBundle\Form\BaseEntityType;
use LSB\UtilityBundle\Form\EntityLazyType;
use Symfony\Component\Form\FormBuilderInterface;
use LSB\ProductBundle\Manager\ProductManager;

/**
 * Class ProductSetProductType
 * @package LSB\ProductBundle\Form
 */
class ProductSetProductType extends BaseEntityType
{

    /**
     * @var ProductManager
     */
    protected ProductManager $productManager;

    /**
     * ProductSetProductType constructor.
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'product',
                EntityLazyType::class,
                [
                    'class' => $this->productManager->getFactory()->getClassName(),
                    'required' => false
                ]
            )
            ->add(
                'productSet',
                EntityLazyType::class,
                [
                    'class' => $this->productManager->getFactory()->getClassName(),
                    'required' => false
                ]
            );
    }
}