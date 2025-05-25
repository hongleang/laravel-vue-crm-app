<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case Enabled = 'enabled';
    case Disabled = 'disabled';
    case Archived = 'archived';
    case Pending = 'pending';

    public function title(): string
    {
        return match ($this) {
            static::Enabled => 'Enabled',
            static::Disabled => 'Disabled',
            static::Archived => 'Archived',
            static::Pending => 'Pending',
        };
    }
}
