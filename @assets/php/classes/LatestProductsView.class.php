<?php

/**
 * @var PDO $pdo
 */

abstract class LatestProductsView
{
    /**
     * @return array[]
     */
    static function get(): array
    {
        global $pdo;

        /** @var string[] $ids */
        $ids = $_SESSION['latestProducts'] ?? [];

        if (!count($ids)) {
            return [];
        }

        /** @var PDOStatement $select */
        $select = $pdo->prepare('
        SELECT id, category, title, image, price
        FROM `products`
        WHERE `id` = :id');

        return array_map(function (string $id) use ($select) {
            $select->bindValue('id', $id);
            $select->execute();

            return $select->fetch(PDO::FETCH_ASSOC);
        }, $ids);
    }

    static function upsert(string $productId): void
    {
        global $config;

        if (!isset($_SESSION['latestProducts'])) {
            $_SESSION['latestProducts'] = [];
        }

        /** @var string[] $ids */
        $ids = array_map(fn($product) => $product['id'], self::get());

        /**
         * Recupero l'eventuale lista di prodotti visualizzati
         * N.B. I prodotti sono ordinati dall'ultimo visualizzato al primo
         */
        $latestProducts = array_filter($ids, fn($id) => $id !== $productId);

        /**
         * Mi assicuro che l'array non superi il valore massimo impostato nel file di configurazione,
         * che è il valore massimo di elementi che vogliamo tracciare.
         */
        $latestProducts = array_slice($latestProducts, 0, $config['products']['latestProducts']['maxLength'] - 1);

        /**
         * Per poter aggiungere un nuovo prodotto, devo riordinarlo dal più vecchio visualizzato al più recente. Perché
         * l'interimento di un nuovo elemento nell'array implica che lo inserisce alla fine dell'array, non all'inizio.
         */
        $latestProducts = array_reverse($latestProducts);

        /**
         * Aggiungo il prodotto visualizzato all'array, che per inciso viene aggiunto alla fine dell'array stesso.
         */
        $latestProducts[] = $productId;

        /**
         * Salvo l'array nella collection di sessione, assicurandomi di salvarlo ordinato dal più recente al meno
         * recente.
         */
        $_SESSION['latestProducts'] = array_reverse($latestProducts);
    }
}
