<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use LSB\UtilityBundle\Interfaces\IdInterface;

/**
 * Interface CategoryTranslationInterface
 * @package LSB\ProductBundle\Entity
 */
interface CategoryTranslationInterface extends IdInterface, TranslationInterface, SluggableInterface
{

}