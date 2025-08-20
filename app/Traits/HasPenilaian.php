<?php

namespace App\Traits;

use App\Helpers\PenilaianHelper;

trait HasPenilaian
{
    public function getPenilaianLabelAttribute(): ?string
    {
        return PenilaianHelper::numericToLabel($this->penilaian);
    }

    public function getPenilaianEmojiAttribute(): string
    {
        return PenilaianHelper::numericWithEmoji($this->penilaian);
    }
}
