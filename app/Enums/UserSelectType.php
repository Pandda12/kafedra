<?php

declare(strict_types=1);

namespace App\Enums;

enum UserSelectType: string
{
    case ALL = 'all';
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case PERSONAL = 'personal';

    public function label(): string
    {
        return match ($this) {
            self::ALL => 'Для всех сотрудников',
            self::FULL_TIME => 'Для всех штатных',
            self::PART_TIME => 'Для всех совместителей',
            self::PERSONAL => 'Персонально'
        };
    }

    public static function getSelectOptions(): array
    {
        return array_map(fn ($option) => [
            'value' => $option->value,
            'label' => $option->label()
        ], self::cases());
    }
}
