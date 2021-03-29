<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Entity;

/**
 * Interface SupplierInterface
 * @package LSB\ProductBundle\Interfaces
 */
interface SupplierInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self;

    /**
     * @return string|null
     */
    public function getCode(): ?string;

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self;

    /**
     * @return string|null
     */
    public function getNumber(): ?string;

    /**
     * @param string|null $number
     * @return $this
     */
    public function setNumber(?string $number): self;

    /**
     * @return int
     */
    public function getType(): int;

    /**
     * @param int $type
     * @return $this
     */
    public function setType(int $type): self;

    /**
     * @return bool
     */
    public function isSeparateShippingPackageRequired(): bool;

    /**
     * @param bool $isSeparateShippingPackageRequired
     * @return $this
     */
    public function setIsSeparateShippingPackageRequired(bool $isSeparateShippingPackageRequired): self;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self;

    /**
     * @return bool
     */
    public function isDefault(): bool;

    /**
     * @param bool $isDefault
     * @return $this
     */
    public function setIsDefault(bool $isDefault): self;

    /**
     * @return bool
     */
    public function isShippingBySupplierEnabled(): bool;

    /**
     * @param bool $isShippingBySupplierEnabled
     * @return $this
     */
    public function setIsShippingBySupplierEnabled(bool $isShippingBySupplierEnabled): self;
}