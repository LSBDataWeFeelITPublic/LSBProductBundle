<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Service;

use DateTime;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Entity\StorageInterface;
use LSB\ProductBundle\Manager\ProductQuantityManager;
use LSB\ProductBundle\Manager\StorageManager;
use LSB\UtilityBundle\Helper\ValueHelper;
use LSB\UtilityBundle\Interfaces\Base\BasePackageInterface;
use LSB\UtilityBundle\Value\Value;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 *
 */
class StorageService
{

    protected StorageManager $storageManager;

    protected ProductQuantityManager $productQuantityManager;

    protected ParameterBagInterface $ps;

    /**
     * @deprecated
     * @var string|int|null
     */
    protected string|int|null $localStorageNumber = null;

    /**
     * @deprecated
     * @var string|int|null
     */
    protected string|int|null $remoteStorageNumber = null;

    /**
     * @deprecated
     * @var array
     */
    protected array $holidays;

    /**
     * @deprecated
     * @var array
     */
    protected array $remoteShippingDaysCustom;

    /**
     * @deprecated
     * @var array
     */
    protected array $orderBundleConfiguration;

    /**
     * @var array
     */
    protected array $reservedQuantity = [];

    /**
     * @var array
     */
    protected array $notReservedQuantity = [];

    /**
     * @param ParameterBagInterface $ps
     * @param ProductQuantityManager $productQuantityManager
     * @param StorageManager $storageManager
     */
    public function __construct(
        ParameterBagInterface $ps,
        ProductQuantityManager $productQuantityManager,
        StorageManager $storageManager
    ) {
        $this->ps = $ps;
        $this->productQuantityManager = $productQuantityManager;
        $this->storageManager = $storageManager;

        //TODO replace with configuration tree
        $this->localStorageNumber = $this->ps->get('localstorage_number');
        $this->remoteStorageNumber = $this->ps->get('remotestorage_number');
        $this->holidays = $this->ps->get('holidays');
        $this->remoteShippingDaysCustom = $this->ps->get('remoteStorage.shippingDays.useCustom');
        $this->orderBundleConfiguration = $this->ps->get('order.bundle');
    }

    /**
     * @param ProductInterface|null $product
     * @param string|null $remoteStorageNumber
     * @return int
     * @throws \Exception
     */
    public function calculateRemoteShippingDays(ProductInterface $product = null, ?string $remoteStorageNumber = null): int
    {
        if ($product && !$this->remoteShippingDaysCustom) {
            return (int)$product->getShippingDays($remoteStorageNumber ? $remoteStorageNumber : $this->remoteStorageNumber);
        }

        $shippingDaysFromDate = $this->getShippingDaysFromDate();

        return $this->getShippingDaysForHolidays($shippingDaysFromDate);
    }

    /**
     * @param ProductInterface $product
     * @return int
     * @throws \Exception
     */
    public function getMinShippingDaysFromRemote(ProductInterface $product): int
    {

        if (!$this->remoteShippingDaysCustom) {
            return $this->productQuantityManager->getRepository()->getMinShippingDateFromRemote($product->getId());
        }

        $shippingDaysFromDate = $this->getShippingDaysFromDate();

        return $this->getShippingDaysForHolidays($shippingDaysFromDate);
    }

    /**
     * @param int $shippingDays
     * @return int
     */
    protected function calculateMaxShippingDaysForPackage(int $shippingDays): int
    {
        $shippingDays -= BasePackageInterface::LOCAL_PACKAGE_MAX_SHIPPING_DAYS;
        return ceil($shippingDays / BasePackageInterface::PACKAGE_MAX_PERIOD) * BasePackageInterface::PACKAGE_MAX_PERIOD + BasePackageInterface::LOCAL_PACKAGE_MAX_SHIPPING_DAYS;
    }

    /**
     * @return array
     */
    public function getReservedQuantityArray(): array
    {
        return $this->reservedQuantity;
    }

    /**
     * @return array
     */
    public function clearReservedQuantityArray(): array
    {
        return $this->reservedQuantity = [];
    }

    /**
     * @param int $type
     * @param int $productId
     * @param int $shippingDays
     * @param int $maxQuantity
     * @param int $reserveQuantity
     * @return int
     */
    protected function checkAndReserveQuantity(
        int $type,
        int $productId,
        int $shippingDays,
        int $maxQuantity,
        int $reserveQuantity
    ): int {
        if (!array_key_exists($productId, $this->notReservedQuantity)
            || array_key_exists($productId, $this->notReservedQuantity) && !array_key_exists($type, $this->notReservedQuantity[$productId])
            || array_key_exists($productId, $this->notReservedQuantity) && array_key_exists($shippingDays, $this->notReservedQuantity[$productId][$type])
        ) {
            if ($type == BasePackageInterface::PACKAGE_TYPE_FROM_LOCAL_STOCK) {
                $this->notReservedQuantity[$productId][$type] = $maxQuantity;
            } elseif ($type == BasePackageInterface::PACKAGE_TYPE_FROM_REMOTE_STOCK && !array_key_exists($shippingDays, $this->notReservedQuantity[$productId][$type])) {
                $this->notReservedQuantity[$productId][$type][$shippingDays] = $maxQuantity;
            }

            return $reserveQuantity;
        }

        if ($type === BasePackageInterface::PACKAGE_TYPE_FROM_LOCAL_STOCK) {
            $availableQuantity = $this->notReservedQuantity[$productId][$type];

            $newAvailableQuantity = $availableQuantity - $reserveQuantity;

            if ($availableQuantity - $reserveQuantity >= 0) {
                $this->notReservedQuantity[$productId][$type] = $newAvailableQuantity;
                return $reserveQuantity;
            } elseif ($availableQuantity - $reserveQuantity < 0) {
                $this->notReservedQuantity[$productId][$type] = 0;
                return $availableQuantity;
            }
        } elseif ($type === BasePackageInterface::PACKAGE_TYPE_FROM_REMOTE_STOCK) {
            $availableQuantity = $this->notReservedQuantity[$productId][$type][$shippingDays];

            $newAvailableQuantity = $availableQuantity - $reserveQuantity;

            if ($availableQuantity - $reserveQuantity >= 0) {
                $this->notReservedQuantity[$productId][$type][$shippingDays] = $newAvailableQuantity;
                return $reserveQuantity;
            } elseif ($availableQuantity - $reserveQuantity < 0) {
                $this->notReservedQuantity[$productId][$type][$shippingDays] = 0;
                return $availableQuantity;
            }
        }

        return 0;
    }

    /**
     * @param int $productId
     * @param int $requestedQuantity
     * @param int $storageType
     * @param int|null $deliveryStorageQuantity
     * @return int
     */
    public function checkReservedQuantity(
        int $productId,
        int $requestedQuantity,
        int $storageType,
        ?int $deliveryStorageQuantity = null
    ): int {
        $reservedQuantity = 0;
        $availableQuantity = 0;

        if (array_key_exists($productId, $this->reservedQuantity) && array_key_exists($storageType, $this->reservedQuantity[$productId])) {
            $reservedQuantity = $this->reservedQuantity[$productId][$storageType];
        }

        $availableQuantity = $deliveryStorageQuantity - $reservedQuantity > 0 ? $deliveryStorageQuantity - $reservedQuantity : 0;

        if ($availableQuantity <= $requestedQuantity) {
            $requestedQuantity = $availableQuantity;
            $this->reservedQuantity[$productId][$storageType] = $availableQuantity + $requestedQuantity;
        } else {
            $this->reservedQuantity[$productId][$storageType] = $reservedQuantity + $requestedQuantity;
        }

        //zwracamy ograniczon?? warto???? stanu
        return $requestedQuantity;
    }

    /**
     * Oblicza dni dostawy dla magazynu zewn??trznego + w zale??no??ci od argumentu uwzgl??dnia magazyn lokalny w ramach magazyn??w zdalnych
     * U??ywane przy scalaniu dostaw
     *
     * @param ProductInterface $product
     * @param Value $quantity
     * @param bool $useLocalAsRemote
     * @param bool $reserveQuantities
     * @param bool $mergeReservations
     * @return array
     * @throws \Exception
     */
    public function calculateRemoteShippingQuantityAndDays(
        ProductInterface $product,
        Value $quantity,
        bool $useLocalAsRemote = false,
        bool $reserveQuantities = false,
        bool $mergeReservations = false
    ): array {
        $quantityLeft = $quantity;
        $backorderQuantity = 0;

        $storageWithQuantity = [];

        //pobieramy kolejne magazyny w kolejno??ci od najkr??tszego czasu dostawy ze stanem magazynowym product
        $deliveryStorages = $this->productQuantityManager->getRepository()->getRemoteDeliveryDaysForProduct($product->getId(), $useLocalAsRemote);

        //tablica zawiera?? b??dzie scalone warto??ci dost??pno??ci ale ograniczone do maksymalnego poziomu w zam??wieniu
        $storages = [];

        $lastMerged = 0;
        $storagesCountBeforeMerge = 0;

        foreach ($deliveryStorages as $deliveryStorage) {
            $deliveryStorageQuantity = $useLocalAsRemote && $deliveryStorage->getStorage()->getType() == StorageInterface::TYPE_LOCAL ? $deliveryStorage->getAvailableAtHand() : $deliveryStorage->getQuantity();

            //Rezerwacja produkt??w
            if ($reserveQuantities) {
                //sprawdzamy czy istnieje ju?? rezerwacja na stan produktu, je??eli tak ograniczamy dost??pny stan
                $deliveryStorageQuantity = $this->checkReservedQuantity($product->getId(), $quantityLeft, ($mergeReservations ? StorageInterface::TYPE_EXTERNAL : $deliveryStorage->getStorage()->getId()), $deliveryStorageQuantity);
            }

            //Tutaj uwzgl??dniamy zarezerwowany wcze??niej stan
            if ($deliveryStorageQuantity >= $quantityLeft) {
                $quantityFromStorage = $quantityLeft;
                $quantityLeft = 0;
            } else {
                $quantityFromStorage = $deliveryStorageQuantity;
                $quantityLeft -= $deliveryStorageQuantity;
            }

            if ($deliveryStorage->getStorage()->getType() === StorageInterface::TYPE_EXTERNAL) {
                $realShippingDays = $this->calculateRemoteShippingDays($product, $deliveryStorage->getStorage()->getNumber());
            } elseif ($deliveryStorage->getStorage()->getType() === StorageInterface::TYPE_LOCAL) {
                $realShippingDays = $product->getShippingDays($this->localStorageNumber);
            } else {
                $realShippingDays = BasePackageInterface::BACKORDER_PACKAGE_ITEM_SHIPPING_DAYS;
            }

            $maxShippingDays = (int)$realShippingDays;

            //Wyliczenie scalonych dostaw

            $closest = $this->getClosest($maxShippingDays, $storages);

            if (array_key_exists($maxShippingDays, $storages)) {
                //scalamy stany do jednego dnia dostawy
                $storages[$maxShippingDays] =
                    [
                        'quantityFromStorage' => ValueHelper::convertToValue($storages[$maxShippingDays]['quantityFromStorage'] + $quantityFromStorage),
                        'shippingDays' => ($realShippingDays > $storages[$maxShippingDays]['shippingDays']) ? $realShippingDays : $storages[$maxShippingDays]['shippingDays'],
                        'isMerged' => true
                    ];
                $lastMerged = 0;
                $storagesCountBeforeMerge++;
            } elseif ($closest && $lastMerged < 2) {
                //je??eli nie ma magazynu z tak?? sam?? ilo??ci dni szukamy zbli??onych termin??w
                //otrzymujemy najbli??szego s??siada, w przypadku u??o??enia od najkr??tszego czasu dostawy, $closest zawsze b??dzie mniejsze

                $storages[$maxShippingDays] =
                    [
                        'quantityFromStorage' =>  ValueHelper::convertToValue($storages[$closest]['quantityFromStorage'] + $quantityFromStorage),
                        'shippingDays' => ($realShippingDays > $storages[$closest]['shippingDays']) ? $realShippingDays : $storages[$maxShippingDays]['shippingDays'],
                        'isMerged' => true
                    ];
                $lastMerged++;
                unset($storages[$closest]);
                $storagesCountBeforeMerge++;
            } else {
                //tworzymy now?? pozycj?? dostawy
                $storages[$maxShippingDays] =
                    [
                        'quantityFromStorage' =>  ValueHelper::convertToValue($quantityFromStorage),
                        'shippingDays' => $realShippingDays,
                        'isMerged' => false,
                    ];
                $lastMerged = 0;
                $storagesCountBeforeMerge++;
            }

            if ($quantityLeft->equals(ValueHelper::createValueZero())) {
                break;
            }
        }

        //$quantity -= $quantityLeft;
        $quantity = $quantity->subtract($quantityLeft);

        if (isset($this->orderBundleConfiguration['backOrder']['enabled'])
            && $this->orderBundleConfiguration['backOrder']['enabled']
            && $quantityLeft > 0) {
            $backorderQuantity = $quantityLeft;
        }

        //TODO replace with value object
        return [
            $quantity, //maksymalny, dost??pny stan mag.
            $storages, //dost??pno??ci z podzia??em na dni (obci??te do poziomu wymaganego przez zam??wienie)
            ValueHelper::convertToValue($backorderQuantity), //wyliczona dost??pno???? na zam??wienie
            $storagesCountBeforeMerge, //ilo???? magazyn??w przed scaleniem
            $deliveryStorages //tablica dost??pno??ci zawieraj??ca, raw data wihtout Value objects
        ];
    }

    /**
     * @param int $sourceMaxShippingDays
     * @param array $storages
     * @param bool $checkGreater
     * @return int|null
     */
    public function getClosest(
        int $sourceMaxShippingDays,
        array &$storages,
        bool $checkGreater = false
    ): ?int {
        $closest = null;

        /**
         * @var int $maxShippingDays
         * @var array $storageData
         */
        foreach ($storages as $maxShippingDays => $storageData) {
            if (abs($sourceMaxShippingDays - $maxShippingDays) <= BasePackageInterface::PACKAGE_MAX_PERIOD
                && ($maxShippingDays < $closest && !$checkGreater || !$closest)) {
                $closest = $maxShippingDays;
            }
        }

        return $closest;
    }


    /**
     * @return int
     * @throws \Exception
     */
    protected function getShippingDaysFromDate(): int
    {
        // tablica mapuj??ca dni tygodnia na ilo???? dni w dostawie
        // klucz reprezentuje numer dnia, 1 - poniedzia??ek, 7 - niedziela
        $dayToShippingDays = [
            "1" => 8, "2" => 7, "3" => 6, "4" => 5, "5" => 11, "6" => 10, "7" => 9
        ];

        $now = new DateTime();
        $dayOfWeek = $now->format('N');
        $hour = (int)$now->format('H');
        $result = $dayToShippingDays[$dayOfWeek];

        // wyj??tek godzinowy na czwartek i godzine 16
        if ("4" == $dayOfWeek && $hour >= 16) {
            $result = 12;
        }

        return (int)$result;
    }

    /**
     * Modyfikacja shippingDays o dni wolne od pracy
     *
     * @param int $shippingDays
     * @return int
     * @throws \Exception
     */
    public function getShippingDaysForHolidays(int $shippingDays = 0): int
    {
        $year = (int)(new DateTime())->format('Y');
        $currentYearHolidays = array_key_exists($year, $this->holidays) ? $this->holidays[$year] : null;

        if ($currentYearHolidays && count($currentYearHolidays)) {
            $nextMondayStr = (new DateTime())->modify('next monday')->format('d-m');
            $nextTuesdayStr = (new DateTime())->modify('next tuesday')->format('d-m');
            $nextWednesdayStr = (new DateTime())->modify('next wednesday')->format('d-m');

            // dodanie jednego dnia do dni dostawy - mo??na w p??tli, ale zdecydowa??em si?? wrzuci?? w ify na przysz??e ewentualne potrzeby
            if (in_array($nextMondayStr, $currentYearHolidays)) {
                $shippingDays++;
            }

            if (in_array($nextTuesdayStr, $currentYearHolidays)) {
                $shippingDays++;
            }

            if (in_array($nextWednesdayStr, $currentYearHolidays)) {
                $shippingDays++;
            }
        }

        return $shippingDays;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function createZeroProductQuantityForNewProduct(ProductInterface $product): bool
    {
        try {
            $firstLocalStorage = $this->storageManager->getRepository()->getFirstLocaleStorage();

            if (!$firstLocalStorage instanceof StorageInterface) {
                return false;
            }

            $productQuantity = ($this->productQuantityManager->createNew())
                ->setProduct($product)
                ->setQuantity(0)
                ->setQuantityAvailableAtHand(0)
                ->setStorage($firstLocalStorage);

            $this->productQuantityManager->persist($productQuantity);
            $this->productQuantityManager->flush();

            return true;
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * @param int $productId
     * @param int $storageId
     * @param int|null $deliveryStorageQuantity
     * @return int
     */
    public function getAvailableQuantity(int $productId, int $storageId, ?int $deliveryStorageQuantity = null): int
    {
        $reservedQuantity = 0;
        if (array_key_exists($productId, $this->reservedQuantity) && array_key_exists($storageId, $this->reservedQuantity[$productId])) {
            $reservedQuantity = $this->reservedQuantity[$productId][$storageId];
        }

        return $deliveryStorageQuantity - $reservedQuantity > 0 ? $deliveryStorageQuantity - $reservedQuantity : 0;
    }

    /**
     * @param int $productId
     * @return int
     */
    public function getAvailableRemoteQuantity(int $productId): int
    {
        return $this->productQuantityManager->getRepository()->getRemoteQuantityForProduct($productId);
    }
}