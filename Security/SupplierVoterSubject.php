<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Security;

use LSB\ProductBundle\Entity\Supplier;

/**
 * Class SupplierVoterSubject
 * @package LSB\ProductBundle\Security
 */
class SupplierVoterSubject
{
    /**
     * @var Supplier
     */
    protected $supplier;

    /**
     * SupplierVoterSubject constructor.
     * @param Supplier $supplier
     */
    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }
}