<?php

declare(strict_types=1);

namespace App\Enums;

enum TaskStatus: string
{
    case PROPOSED = 'proposed';
    case ASSIGNED = 'assigned';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case REVISED = 'revised';
    case CLOSED = 'closed';
    case REVIEW = 'review';
    case DELETE = 'delete';

    public function label(): string
    {
        return match ($this) {
            self::PROPOSED => 'Предложена',
            self::ASSIGNED => 'Назначена',
            self::ACCEPTED => 'Принята в работу',
            self::REVIEW => 'На проверке',
            self::REJECTED => 'Отклонена',
            self::REVISED => 'Принята на доработку',
            self::CLOSED => 'Закрыта',
            self::DELETE => 'Удалена',
        };
    }

    public static function getReportStatuses(): array
    {
        return array_map(fn ($option) => [
            'value' => $option->value,
            'label' => $option->label()
        ], [
            self::ASSIGNED,
            self::ACCEPTED,
            self::REJECTED,
            self::REVISED,
            self::CLOSED,
            self::REVIEW
        ]);
    }
}
