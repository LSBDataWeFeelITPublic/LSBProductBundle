<?php

namespace LSB\ProductBundle;

use LSB\ProductBundle\DependencyInjection\Compiler\AddManagerResourcePass;
use LSB\ProductBundle\DependencyInjection\Compiler\AddResolveEntitiesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class LSBProductBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder
            ->addCompilerPass(new AddManagerResourcePass())
            ->addCompilerPass(new AddResolveEntitiesPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1);
        ;
    }
}
