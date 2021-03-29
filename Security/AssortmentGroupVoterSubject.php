<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Security;

use LSB\ProductBundle\Entity\AssortmentGroup;

/**
 * Class AssortmentGroupVoterSubject
 * @package LSB\ProductBundle\Security
 */
class AssortmentGroupVoterSubject
{
    /**
     * @var AssortmentGroup
     */
    protected $assortmentGroup;

    /**
     * @param AssortmentGroup $assortmentGroup
     */
    public function __constructor(AssortmentGroup $assortmentGroup)
    {
        $this->assortmentGroup = $assortmentGroup;
    }
}