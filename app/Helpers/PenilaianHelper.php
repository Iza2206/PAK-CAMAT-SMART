<?php

namespace App\Helpers;

class PenilaianHelper
{
    const MAP = [
        'tidak_puas'   => 1,
        'cukup'        => 2,
        'puas'         => 3,
        'sangat_puas'  => 4,
    ];

    const MAP_REVERSE = [
        1 => 'tidak_puas',
        2 => 'cukup',
        3 => 'puas',
        4 => 'sangat_puas',
    ];

    const LABELS = [
        1 => '😠 Tidak Puas',
        2 => '😐 Cukup',
        3 => '🙂 Puas',
        4 => '🤩 Sangat Puas',
    ];

    public static function labelToNumeric(string $label): ?int
    {
        return self::MAP[$label] ?? null;
    }

    public static function numericToLabel(int $value): ?string
    {
        return self::MAP_REVERSE[$value] ?? null;
    }

    public static function numericWithEmoji(int $value): string
    {
        return self::LABELS[$value] ?? '-';
    }

    public static function allOptions(): array
    {
        return array_combine(
            array_keys(self::MAP),
            array_values(self::LABELS)
        );
    }

    public static function validationRule(): string
    {
        return 'required|in:' . implode(',', array_keys(self::MAP));
    }
}
