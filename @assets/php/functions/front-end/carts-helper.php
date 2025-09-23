<?php

/**
 * Verifichiamo se un prodotto è nel nostro carrello
 *
 * @param string $productId
 * @return bool
 */
function inCart(string $productId): bool
{
    return in_array($productId, $_SESSION['cart'] ?? []);
}
