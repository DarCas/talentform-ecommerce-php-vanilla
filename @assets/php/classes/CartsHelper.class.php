<?php

abstract class CartsHelper
{
    /**
     * Restituisce il nostro carrello
     *
     * @return array[]
     */
    static function get(): array
    {
        global $pdo;

        /** @var string[] $ids */
        $ids = $_SESSION['cart'] ?? [];

        if (!count($ids)) {
            return [];
        }

        /** @var PDOStatement $select */
        $select = $pdo->prepare('
        SELECT id, title, image, price, qty, description
        FROM `products`
        WHERE `id` = :id');

        return array_map(function (string $id) use ($select) {
            $select->bindValue('id', $id);
            $select->execute();

            return $select->fetch(PDO::FETCH_ASSOC);
        }, $ids);
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
     * Il valore totale del nostro carrello
     *
     * @return float
     */
    static function amountCart(): float
    {
        return array_reduce(self::get(), function ($somma, $item) {
            $somma += $item['price'];

            return $somma;
        }, 0);
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

    /**
     * Svuoto il carrello
     *
     * @return void
     */
    static function removeAll(): void
    {
        $_SESSION['cart'] = [];
    }
}
