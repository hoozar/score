<?php
declare(strict_types=1);

namespace App\Entity;

enum EventTypeEnum: string
{
    case FOUL = 'foul';
    case GOAL = 'goal';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
