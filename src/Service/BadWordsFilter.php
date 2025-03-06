<?php

namespace App\Service;

class BadWordsFilter
{
    private array $badWords = ['dog', 'vulgaire', 'noir']; // Liste des mots interdits

    public function containsBadWord(string $text): bool
    {
        foreach ($this->badWords as $word) {
            if (stripos($text, $word) !== false) { // Vérifie si un mot interdit est présent
                return true;
            }
        }
        return false;
    }
}
