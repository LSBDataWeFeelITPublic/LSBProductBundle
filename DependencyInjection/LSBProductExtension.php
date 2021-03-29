<?php
declare(strict_types=1);

namespace LSB\ProductBundle\DependencyInjection;

use LSB\UtilityBundle\DependencyInjection\BaseExtension;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class LSBProductExtension extends BaseExtension
{
    const CONFIG_PREFIX = 'lsb_product';
    protected $dir = __DIR__;

    /**
     * @return ConfigurationInterface
     */
    public function getTreeConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
