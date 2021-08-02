<?php

namespace LSB\ProductBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use LSB\OrderBundle\Entity\OrderPackage;
use LSB\OrderBundle\Entity\PackageItem;
use LSB\ProductBundle\Entity\Product;
use LSB\ProductBundle\Entity\ProductQuantity;
use LSB\ProductBundle\Entity\Storage;
use LSB\ProductBundle\Exception\StorageException;
use LSB\UtilBundle\Service\ParameterService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 *
 */
class StorageBookingService
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var ParameterBagInterface
     */
    protected ParameterBagInterface $ps;

    /**
     * @var StorageManager
     */
    protected $storageManager;

    /**
     * StorageBookingManager constructor.
     * @param EntityManagerInterface $em
     * @param ParameterService $ps
     * @param StorageManager $storageManager
     */
    public function __construct(
        EntityManagerInterface $em,
        ParameterService $ps,
        StorageManager $storageManager
    ) {
        $this->em = $em;
        $this->ps = $ps;
        $this->storageManager = $storageManager;
    }

    /**
     * Metoda rezerwująca stan magazynowy dla całej paczki
     * Rezerwacja dokonywana jest tylko wtedy, gdy lokalny stan magazynowy pozwala w całości zrealizować paczkę
     * Przed wykonaniem tej operacji należy najpierw zweryfikować możliwość jej przeprowadzenia
     *
     * @param OrderPackage $orderPackage
     * @param Storage|null $storage
     * @param bool $flush
     * @return bool
     * @throws StorageException
     */
    public function bookOrderPackageItems(OrderPackage $orderPackage, ?Storage $storage = null, $flush = false): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->getParameter('localstorage_number'));
        }

        $this->checkStorageCompatibility($storage);

        if ($orderPackage->getItems()->count() && !$orderPackage->getIsStockReserved()) {

            $isOrderPackageBooked = true;

            $reservedQty = [];
            $products = [];
            $bookedProducts = [];

            /**
             * @var PackageItem $packageItem
             *
             * Wyliczenie rezerwacji
             */
            foreach ($orderPackage->getItems() as $packageItem) {
                if ($packageItem->getProduct() && array_key_exists($packageItem->getProduct()->getId(), $reservedQty)) {
                    $reservedQty[$packageItem->getProduct()->getId()] = $packageItem->getQuantity() + $reservedQty[$packageItem->getProduct()->getId()];
                    $products[$packageItem->getProduct()->getId()] = $packageItem->getProduct();
                } elseif ($packageItem->getProduct()) {
                    $reservedQty[$packageItem->getProduct()->getId()] = $packageItem->getQuantity();
                    $products[$packageItem->getProduct()->getId()] = $packageItem->getProduct();
                }
            }

            /**
             * Bookowanie
             *
             * @var Product $product
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

            //Oznaczanie pozycji w paczce jako zabookowane
            /**
             * @var PackageItem $packageItem
             */
            foreach ($orderPackage->getItems() as $packageItem) {
                if ($packageItem->getProduct() && array_key_exists($packageItem->getProduct()->getId(), $bookedProducts)) {
                    $packageItem->book($storage);
                }
            }

            //Nawet gdy niektórych pozycji nie udało się zabookować, oznaczamy paczkę jako zabookowaną
            $orderPackage->setIsStockReserved(true);

            if ($flush) {
                $this->em->flush();
            }
            return true;
        }

        return false;
    }

    /**
     * Metoda zwalnia rezerwację stanu magazynowego
     *
     * @param OrderPackage $orderPackage
     * @param bool $flush
     * @return bool
     */
    public function unbookOrderPackageItems(OrderPackage $orderPackage, $flush = false): bool
    {
        $products = [];
        $reservedQty = [];
        $unbookedProducts = [];
        $storages = [];

        if ($orderPackage->getItems()->count() && $orderPackage->getIsStockReserved()) {

            /**
             * Wyliczenie sumarycznej ilości rezerwacji
             *
             * @var PackageItem $packageItem
             */
            foreach ($orderPackage->getItems() as $packageItem) {
                if ($packageItem->getIsStockReserved()) {

                    $storage = $packageItem->getBookingStorage();

                    if (!$storage) {
                        $storage = $this->getLocalStorage($this->ps->getParameter('localstorage_number'));
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

            //Aktualizacja stanu mag.
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
             * Zdejmowanie rezerwacji z pozycji
             *
             * @var PackageItem $packageItem
             */
            foreach ($orderPackage->getItems() as $packageItem) {
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
                $this->em->flush();
            }

            return true;
        }

        return false;
    }

    /**
     * @param OrderPackage $orderPackage
     * @param Storage|null $storage
     * @param bool $flush
     */
    public function refreshBookingOrderPackageItems(OrderPackage $orderPackage, ?Storage $storage = null, $flush = true): void
    {
        if (!$orderPackage->getIsStockReserved()) {
            return;
        }

        $this->unbookOrderPackageItems($orderPackage, true);
        //Sprawdzamy czy w obecnej sytuacji wykonanie bookowania będzie możliwe
        $this->startReservation();
        if ($this->checkLocalBookingForOrderPackage($orderPackage)) {
            $this->bookOrderPackageItems($orderPackage);
        }

        if ($flush) {
            $this->em->flush();
        }
    }


    /**
     * Metoda zwalniająca rezerwację stanu magazynowego
     *
     * @param Product $product
     * @param Storage|null $storage
     * @param int $bookQuantity
     * @return bool
     */
    public function unbookQuantity(Product $product, ?Storage $storage, int $bookQuantity): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->getParameter('localstorage_number'));
        }

        $productQuantity = $this->getProductQuantity($product, $storage);

        if (!($productQuantity instanceof ProductQuantity) || !$this->isBookingAvailable($productQuantity, $bookQuantity)) {
            return false;
        }

        $productQuantity->unbookQuantity($bookQuantity);

        return true;
    }

    /**
     * Metoda rezerwująca stan magazynowy dla produktu
     * Bookowanie dostępne jest wyłącznie w magazynie lokalnym
     *
     * @param Product $product
     * @param Storage|null $storage
     * @param int $bookQuantity
     * @return bool
     * @throws StorageException
     */
    public function bookQuantity(Product $product, ?Storage $storage, int $bookQuantity): bool
    {
        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->getParameter('localstorage_number'));
        }

        //Sprawdzamy zgodność magazynu
        $this->checkStorageCompatibility($storage);

        $productQuantity = $this->getProductQuantity($product, $storage);

        //Nie udało się pobrać stanu mag. nie możemy dokonać rezerwacji
        if (!($productQuantity instanceof ProductQuantity) || !$this->isBookingAvailable($productQuantity, $bookQuantity)) {
            return false;
        }

        //Możemy dokonać rezerwacji
        $productQuantity->bookQuantity($bookQuantity);

        return true;
    }

    /**
     * Sprawdza dostępność produktu w magazynie lokalnym już po złożeniu zamówienia
     * Zwraca status bookowania dla całej paczki (w oparciu o rezerwację wstępną)
     *
     * @param OrderPackage $orderPackage
     * @param Storage|null $storage
     * @return bool
     */
    public function checkLocalBookingForOrderPackage(OrderPackage $orderPackage, ?Storage $storage = null): bool
    {
        $book = true;

        if (!$storage) {
            $storage = $this->getLocalStorage($this->ps->getParameter('localstorage_number'));
        }

        /**
         * @var PackageItem $packageItem
         */
        foreach ($orderPackage->getItems() as $packageItem) {
            //Obecnie sprawdzamy tylko stan lokalny

            $productQuantity = $this->getProductQuantity($packageItem->getProduct(), $storage);
            if (!($productQuantity instanceof ProductQuantity)) {
                $book = false;
                break; //łamie dwa foreach
            }

            $rawLocalQuantity = $productQuantity->getAvailableAtHand();

            $availablelocalQuantity = $this->storageManager->checkReservedQuantity($packageItem->getProduct()->getId(), $packageItem->getQuantity(), Storage::TYPE_LOCAL, $rawLocalQuantity);

            if ($availablelocalQuantity < $packageItem->getQuantity()) {
                $book = false;
                break; //łamie dwa foreach
            }
        }


        return $book;
    }

    /**
     * Metoda czyści tymaczasową rezerwację stanów
     */
    public function startReservation(): void
    {
        $this->storageManager->clearReservedQuantityArray();
    }

    /**
     * @param string $number
     * @return Storage|null
     */
    protected function getLocalStorage(string $number): ?Storage
    {
        $localStorage = $this->em->getRepository(Storage::class)->getLocalStorageByNumber($number);

        return $localStorage;
    }

    /**
     * @param Product $product
     * @param Storage $storage
     * @return ProductQuantity|null
     */
    protected function getProductQuantity(Product $product, Storage $storage): ?ProductQuantity
    {
        $productQuantity = $this->em->getRepository(ProductQuantity::class)->getProductQuantityByProductAndStorage($product->getId(), $storage->getId());

        return $productQuantity;
    }

    /**
     * Sprawdza czy magazyn jest przytosowany do obsługi rezerwacji
     *
     * @param $storage
     * @return bool
     * @throws StorageException
     */
    protected function checkStorageCompatibility(?Storage $storage): bool
    {
        if (!($storage instanceof Storage)) {
            throw new StorageException('Missing storage object');
        }

        if ($storage->getType() !== Storage::TYPE_LOCAL) {
            throw new StorageException(sprintf('Storage %s, (%s) with type %s does no support booking', $storage->getName(), $storage->getId(), $storage->getType()));
        }

        return true;
    }

    /**
     * Sprawdzamy możliwość dokonania rezerwacji
     * Domyślnie pozwalamy na wykonani rezerwacji nawety gdy zamówienie przekracza wartość lokalnego magazynu
     *
     * @param ProductQuantity $productQuantity
     * @param int $bookQuantity
     * @param bool $availableBackorder
     * @return bool
     */
    protected function isBookingAvailable(ProductQuantity $productQuantity, int $bookQuantity, bool $availableBackorder = true): bool
    {
        if ($productQuantity->getAvailableAtHand() < 0
            || $bookQuantity > $productQuantity->getAvailableAtHand() && !$availableBackorder

        ) {
            return false;
        }

        return true;
    }

}
