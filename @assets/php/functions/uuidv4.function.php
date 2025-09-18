<?php

/**
 * Funzione per verificare se la stringa inviata è uno UUID versione 4
 *
 * @param string $uuid
 * @return bool
 */
function UuidV4Validate(string $uuid): bool
{
    $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    return (bool)preg_match($pattern, $uuid);
}

function UuidV4(): string
{
    // genera 16 byte (128 bit) casuali
    $data = random_bytes(16);

    // imposta i bit della versione (0100 per v4)
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);

    // imposta i bit del variant (10xx)
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

    // formatta come 8-4-4-4-12 esadecimale
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
