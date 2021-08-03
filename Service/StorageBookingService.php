<?php
declare(strict_types=1);

namespace LSB\ProductBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\Pure;
use LSB\OrderBundle\Entity\OrderPackage;
use LSB\OrderBundle\Entity\OrderPackageInterface;
use LSB\OrderBundle\Entity\OrderPackageItem;
use LSB\OrderBundle\Entity\OrderPackageItemInterface;
use LSB\OrderBundle\Entity\PackageItem;
use LSB\OrderBundle\Entity\PackageItemInterface;
use LSB\OrderBundle\Manager\OrderPackageManager;
use LSB\ProductBundle\Entity\Product;
use LSB\ProductBundle\Entity\ProductInterface;
use LSB\ProductBundle\Entity\ProductQuantity;
use LSB\ProductBundle\Entity\ProductQuantityInterface;
use LSB\ProductBundle\Entity\Storage;
use LSB\ProductBundle\Entity\StorageInterface;
use LSB\ProductBundle\Exception\StorageException;
use LSB\ProductBundle\Manager\ProductQuantityManager;
use LSB\ProductBundle\Manager\StorageManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class StorageBookingService
{

    protected ProductQuantityManager $productQuantityManager;

    protected OrderPackageManager $orderPackageManager;

    protected EntityManagerInterface $em;

    protected ParameterBagInterface $ps;

    protected StorageManager $storageManager;

    protected StorageService $storageService;

    /**
     * StorageBookingManager constructor.
     * @param EntityManagerInterface $em
     * @param ParameterBagInterface $ps
     * @param StorageManager $storageManager
     * @param StorageService $storageService
     * @param ProductQuantityManager $productQuantityManager
     * @param OrderPackageManager $orderPackageManager
     */
    public function __construct(
        EntityManagerInterface $em,
        ParameterBagInterface $ps,
        StorageManager $storageManager,
        StorageService $storageService,
        ProductQuantityManager $productQuantityManager,
        OrderPackageManager $orderPackageManager
    ) {
        $this->em = $em;
        $this->ps = $ps;
        $this->storageManager = $storageManager;
        $this->storageService = $storageService;
        $this->productQuantityManager = $productQuantityManager;
        $this->orderPackageManager = $orderPackageManager;
    }

    /**
     * Method makes a product quantity resevervation
     * Reservations are made only when the local inventory is sufficient to fulfill the package in full
     * Before performing this operation, you must first verify its feasibility
     *
     * @param OrderPackageInterface $orderPackage
     * @param StorageInterface|null $storage
     * @param bool $flush
     * @return bool
     * @throws StorageException
     */
    public function bookOrderPackageItems(OrderPackageInterface $orderPackage, ?StorageInterface $storage = null, bool $flush = false): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->get('localstorage_number'));
        }

        $this->checkStorageCompatibility($storage);

        /**
         * Temporary
         * @var OrderPackage $orderPackage
         */
        if ($orderPackage->getOrderPackageItems()->count() && !$orderPackage->isStockReserved()) {
            $isOrderPackageBooked = true;

            $reservedQty = [];
            $products = [];
            $bookedProducts = [];

            /**
             * @var OrderPackageItem $orderPackageItem
             */
            foreach ($orderPackage->getOrderPackageItems() as $orderPackageItem) {
                if ($orderPackageItem->getProduct() && array_key_exists($orderPackageItem->getProduct()->getId(), $reservedQty)) {
                    $reservedQty[$orderPackageItem->getProduct()->getId()] = $orderPackageItem->getQuantity() + $reservedQty[$orderPackageItem->getProduct()->getId()];
                    $products[$orderPackageItem->getProduct()->getId()] = $orderPackageItem->getProduct();
                } elseif ($orderPackageItem->getProduct()) {
                    $reservedQty[$orderPackageItem->getProduct()->getId()] = $orderPackageItem->getQuantity();
                    $products[$orderPackageItem->getProduct()->getId()] = $orderPackageItem->getProduct();
                }
            }

            /**
             * @var ProductInterface $product
             */
            foreach ($products as $product) {
                if (array_key_exists($product->getId(), $reservedQty)) {
                    $booked = $this->bookQuantity($product, $storage, $reservedQty[$product->getId()]);
                    if ($booked) {
                        $bookedProducts[$product->getId()] = true;
                    } else {
                        $isOrderPackageBooked = false;
                    }
                }
            }

            /**
             * @var OrderPackageItemInterface $packageItem
             */
            foreach ($orderPackage->getOrderPackageItems() as $orderPackageItem) {
                if ($orderPackageItem->getProduct() && array_key_exists($orderPackageItem->getProduct()->getId(), $bookedProducts)) {
                    $orderPackageItem->book($storage);
                }
            }

            $orderPackage->setIsStockReserved(true);

            if ($flush) {
                $this->orderPackageManager->update($orderPackage);
            }
            return true;
        }

        return false;
    }

    /**
     * @param OrderPackageInterface $orderPackage
     * @param bool $flush
     * @return bool
     */
    public function unbookOrderPackageItems(OrderPackageInterface $orderPackage, bool $flush = false): bool
    {
        $products = [];
        $reservedQty = [];
        $unbookedProducts = [];
        $storages = [];

        if ($orderPackage->getOrderPackageItems()->count() && $orderPackage->isStockReserved()) {

            /**
             * @var PackageItem $packageItem
             */
            foreach ($orderPackage->getOrderPackageItems() as $packageItem) {
                if ($packageItem->getIsStockReserved()) {
                    $storage = $packageItem->getBookingStorage();

                    if (!$storage) {
                        $storage = $this->getLocalStorage($this->ps->get('localstorage_number'));
                    }

                    if (!array_key_exists($storage->getId(), $reservedQty)) {
                        $reservedQty[$storage->getId()] = [];
                        $storages[$storage->getId()] = $storage;
                    }

                    if ($packageItem->getProduct() && array_key_exists($packageItem->getProduct()->getId(), $reservedQty[$storage->getId()])) {
                        $reservedQty[$storage->getId()][$packageItem->getProduct()->getId()] = $packageItem->getQuantity() + $reservedQty[$storage->getId()][$packageItem->getProduct()->getId()];
                        $products[$packageItem->getProduct()->getId()] = $packageItem->getProduct();
                    } elseif ($packageItem->getProduct()) {
                        $reservedQty[$storage->getId()][$packageItem->getProduct()->getId()] = $packageItem->getQuantity();
                        $products[$packageItem->getProduct()->getId()] = $packageItem->getProduct();
                    }
                }
            }

            foreach ($reservedQty as $storageId => $reservation) {
                $storage = null;
                if (array_key_exists($storageId, $storages)) {
                    $storage = $storages[$storageId];
                }

                foreach ($reservation as $productId => $qty) {
                    if (!array_key_exists($productId, $products)) {
                        continue;
                    }

                    $product = $products[$productId];
                    $productQuantity = $this->getProductQuantity($product, $storage);

                    if ($productQuantity instanceof ProductQuantity) {
                        $productQuantity->unbookQuantity($qty);
                        $unbookedProducts[$storageId][$productId] = $qty;
                    }
                }
            }

            /**
             * Releasing product quantity reservation
             *
             * @var PackageItemInterface $packageItem
             */
            foreach ($orderPackage->getOrderPackageItems() as $packageItem) {
                if ($packageItem->getProduct()
                    && $packageItem->getBookingStorage()
                    && array_key_exists($packageItem->getBookingStorage()->getId(), $unbookedProducts)
                    && array_key_exists($packageItem->getProduct()->getId(), $unbookedProducts[$packageItem->getBookingStorage()->getId()])
                ) {
                    $packageItem->unbook();
                }
            }

            $orderPackage->setIsStockReserved(false);

            if ($flush) {
                $this->orderPackageManager->update($orderPackage);
            }

            return true;
        }

        return false;
    }

    /**
     * @param OrderPackageInterface $orderPackage
     * @param StorageInterface|null $storage
     * @param bool $flush
     * @throws StorageException
     */
    public function refreshBookingOrderPackageItems(OrderPackageInterface $orderPackage, ?StorageInterface $storage = null, bool $flush = true): void
    {
        if (!$orderPackage->isStockReserved()) {
            return;
        }

        $this->unbookOrderPackageItems($orderPackage, true);

        $this->startReservation();
        if ($this->checkLocalBookingForOrderPackage($orderPackage)) {
            $this->bookOrderPackageItems($orderPackage);
        }

        if ($flush) {
            $this->orderPackageManager->update($orderPackage);
        }
    }


    /**
     * @param ProductInterface $product
     * @param StorageInterface|null $storage
     * @param int $bookQuantity
     * @return bool
     */
    public function unbookQuantity(ProductInterface $product, ?StorageInterface $storage, int $bookQuantity): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->get('localstorage_number'));
        }

        $productQuantity = $this->getProductQuantity($product, $storage);

        if (!($productQuantity instanceof ProductQuantityInterface) || !$this->isBookingAvailable($productQuantity, $bookQuantity)) {
            return false;
        }

        $productQuantity->unbookQuantity($bookQuantity);

        return true;
    }

    /**
     * @param ProductInterface $product
     * @param StorageInterface|null $storage
     * @param int $bookQuantity
     * @return bool
     * @throws StorageException
     */
    public function bookQuantity(ProductInterface $product, ?StorageInterface $storage, int $bookQuantity): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->get('localstorage_number'));
        }

        $this->checkStorageCompatibility($storage);

        $productQuantity = $this->getProductQuantity($product, $storage);

        //Nie udało się pobrać stanu mag. nie możemy dokonać rezerwacji
        if (!($productQuantity instanceof ProductQuantityInterface) || !$this->isBookingAvailable($productQuantity, $bookQuantity)) {
            return false;
        }

        $productQuantity->bookQuantity($bookQuantity);

        return true;
    }

    /**
     * Checks the availability of the product in the local stock, after placing the order
     * Returns the booking status for the entire package
     *
     * @param OrderPackageInterface $orderPackage
     * @param StorageInterface|null $storage
     * @return bool
     */
    public function checkLocalBookingForOrderPackage(OrderPackageInterface $orderPackage, ?StorageInterface $storage = null): bool
    {
        $book = true;

        //TODO refactor
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->get('localstorage_number'));
        }

        /**
         * @var OrderPackageItem $packageItem
         */
        foreach ($orderPackage->getOrderPackageItems() as $packageItem) {
            $productQuantity = $this->getProductQuantity($packageItem->getProduct(), $storage);
            if (!($productQuantity instanceof ProductQuantity)) {
                $book = false;
                break;
            }

            $rawLocalQuantity = $productQuantity->getQuantityAvailableAtHand();
            $availablelocalQuantity = $this->storageService->checkReservedQuantity($packageItem->getProduct()->getId(), $packageItem->getQuantity(), StorageInterface::TYPE_LOCAL, $rawLocalQuantity);

            if ($availablelocalQuantity < $packageItem->getQuantity()) {
                $book = false;
                break;
            }
        }

        return $book;
    }

    public function startReservation(): void
    {
        $this->storageService->clearReservedQuantityArray();
    }

    /**
     * @param string $number
     * @return Storage|null
     */
    protected function getLocalStorage(string $number): ?StorageInterface
    {
        return $this->storageManager->getRepository()->getLocalStorageByNumber($number);
    }

    /**
     * @param Product $product
     * @param Storage $storage
     * @return ProductQuantity|null
     */
    protected function getProductQuantity(ProductInterface $product, StorageInterface $storage): ?ProductQuantityInterface
    {
        return $this->productQuantityManager->getRepository()->getProductQuantityByProductAndStorage($product->getId(), $storage->getId());
    }

    /**
     * @param StorageInterface|null $storage
     * @return bool
     * @throws StorageException
     */
    protected function checkStorageCompatibility(?StorageInterface $storage = null): bool
    {

        if (!$storage instanceof StorageInterface) {
            throw new StorageException('Storage object is required');
        }

        if ($storage->getType() !== StorageInterface::TYPE_LOCAL) {
            throw new StorageException(sprintf('Storage %s, (%s) with type %s does no support booking', $storage->getName(), $storage->getId(), $storage->getType()));
        }

        return true;
    }

    /**
     * @param ProductQuantityInterface $productQuantity
     * @param int $bookQuantity
     * @param bool $availableBackorder
     * @return bool
     */
    #[Pure] protected function isBookingAvailable(ProductQuantityInterface $productQuantity, int $bookQuantity, bool $availableBackorder = true): bool
    {
        if ($productQuantity->getQuantityAvailableAtHand() < 0
            || $bookQuantity > $productQuantity->getQuantityAvailableAtHand() && !$availableBackorder
        ) {
            return false;
        }

        return true;
    }
}