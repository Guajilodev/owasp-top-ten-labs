<?php

/**
 * LegacyHtmlSanitizer v1.0.0 — Librería simulada de sanitización HTML.
 *
 * VULNERABILIDAD: Esta "librería" solo filtra etiquetas <script>, pero ignora
 * otros vectores de XSS como <img onerror>, <svg onload>, <body onload>,
 * event handlers en atributos, etc.
 *
 * La "versión 2.0.0" (que este proyecto nunca actualizó) corrige estos bypasses
 * usando una whitelist de etiquetas y atributos permitidos en vez de una blacklist.
 */
class LegacyHtmlSanitizer
{
    const VERSION = '1.0.0';

    /**
     * Elimina etiquetas <script> del input.
     * INSUFICIENTE: Solo cubre un vector de ataque.
     */
    public static function clean(string $input): string
    {
        // Elimina etiquetas <script>...</script>
        $cleaned = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $input);

        // Elimina etiquetas <script> sin cierre
        $cleaned = preg_replace('/<script\b[^>]*\/?>/i', '', $cleaned);

        return $cleaned;
    }
}
