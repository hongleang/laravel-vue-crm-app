<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case Pending = 'pending';
    case InProgress = 'in-progress';
    case Completed = 'completed';
    case Archived = 'archived';

    public function title(): string
    {
        return match ($this) {
            static::Pending => 'Pending',
            static::InProgress => 'In Progress',
            static::Completed => 'Completed',
            static::Archived => 'Archived',
        };
    }
}
