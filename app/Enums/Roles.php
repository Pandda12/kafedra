<?php

declare(strict_types=1);

namespace App\Enums;

enum Roles: string
{
    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case FULL_TIME_EMPLOYEE = 'full-time';
    case PART_TIME_EMPLOYEE = 'part-time';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Администратор',
            self::EMPLOYEE => 'Сотрудник',
            self::FULL_TIME_EMPLOYEE => 'Штатный сотрудник',
            self::PART_TIME_EMPLOYEE => 'Совместитель'
        };
    }

    public static function getCreateRoles(): array
    {
        return array_map(fn ($option) => [
            'value' => $option->value,
            'label' => $option->label()
        ], [
            self::ADMIN,
            self::EMPLOYEE
        ]);
    }

    public static function getAssigneeRoles(): array
    {
        return array_map(fn ($option) => [
            'value' => $option->value,
            'label' => $option->label()
        ], [
            self::FULL_TIME_EMPLOYEE,
            self::PART_TIME_EMPLOYEE
        ]);
    }

    public static function getSelectOptions(): array
    {
        return array_map(fn ($option) => [
            'value' => $option->value,
            'label' => $option->label()
        ], [
            self::ADMIN,
            self::FULL_TIME_EMPLOYEE,
            self::PART_TIME_EMPLOYEE
        ]);
    }

    public static function getImportRoles(): array
    {
        return [
            [
                'value' => self::FULL_TIME_EMPLOYEE->value,
                'label' => self::FULL_TIME_EMPLOYEE->label()
            ],
            [
                'value' => self::PART_TIME_EMPLOYEE->value,
                'label' => self::PART_TIME_EMPLOYEE->label()
            ],
        ];
    }
}
