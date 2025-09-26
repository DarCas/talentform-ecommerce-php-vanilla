<?php

class NumberFormat
{
    static function formatCurrencyAmazon($amount, $currency): string
    {
        $formatter = NumberFormatter::create('it_IT', NumberFormatter::CURRENCY);
        $priceFormatted = $formatter->formatCurrency($amount, $currency);

        $parts = explode(',', $priceFormatted);

        return "{$parts[0]}<sup>{$parts[1]}</sup>";
    }
}
