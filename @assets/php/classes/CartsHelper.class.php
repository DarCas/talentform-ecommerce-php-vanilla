<?php

abstract class CartsHelper
{
    /**
     * Restituisce il nostro carrello
     *
     * @return string[]
     */
    static function get(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Restituisce il numero di prodotti nel nostro carrello
     *
     * @return int
     */
    static function count(): int
    {
        return count($_SESSION['cart'] ?? []);
    }

    /**
     * Verifichiamo se un prodotto è nel nostro carrello
     *
     * @param string $productId
     * @return bool
     */
    static function inCart(string $productId): bool
    {
        return in_array($productId, $_SESSION['cart'] ?? []);
    }

    /**
     * Aggiungiamo un prodotto al nostro carrello
     *
     * @param string $productId
     * @return bool
     */
    static function add(string $productId): bool
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (!in_array($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][] = $productId;
        }

        return in_array($productId, $_SESSION['cart']);
    }

    /**
     * Rimuoviamo un prodotto dal nostro carrello
     *
     * @param string $productId
     * @return void
     */
    static function remove(string $productId): void
    {
        if (in_array($productId, $_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], fn($id) => $id !== $productId);
        }
    }
}
