<?php
// src/Service/ProductService.php
namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Načte jeden produkt z databáze podle jeho ID.
     *
     * @param int $productId
     * @return Product|null
     */
    public function getProductById(int $productId): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->find($productId);
    }

    /**
     * Načte produkty z databáze podle jejich ID.
     *
     * @param array $productIds
     * @return array
     */
    public function getProductsByIds(array $productIds): array
    {
        // Vytvoření dotazu na načtení produktů podle ID
        $products = $this->entityManager->getRepository(Product::class)->findBy(['id' => $productIds]);

        return $products;
    }
}
