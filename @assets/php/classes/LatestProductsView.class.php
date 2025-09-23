<?php

abstract class LatestProductsView
{
    static function get(): array
    {
        return $_SESSION['latestProducts'] ?? [];
    }

    static function upsert(string $productId): void
    {
        if (!isset($_SESSION['latestProducts'])) {
            $_SESSION['latestProducts'] = [];
        }

        /**
         * Recupero l'eventuale lista di prodotti visualizzati
         * N.B. I prodotti sono ordinati dall'ultimo visualizzato al primo
         */
        $latestProducts = array_filter(self::get(), fn($id) => $id !== $productId);

        /**
         * Mi assicuro che l'array non superi i 4 elementi perché devo aggiungere un altro che porterà il computo a 5,
         * che è il valore massimo di elementi che vogliamo tracciare.
         */
        $latestProducts = array_slice($latestProducts, 0, 4);

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
