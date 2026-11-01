<?php
namespace Traits;

// Provides simple logic to parse a timestamp
trait HasTimestamps
{
    private function parse_date($timestamp): string
    {
        $parsed = date_parse($timestamp);

        // Fallback or safety check if parsing fails completely
        if ($parsed === false || !isset($parsed['day'], $parsed['month'], $parsed['year'])) {
            return '';
        }

        return sprintf('%02d', $parsed['day']) . '-' . 
               sprintf('%02d', $parsed['month']) . '-' . 
               $parsed['year'];
    }
}