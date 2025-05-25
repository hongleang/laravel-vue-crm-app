<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Admin = 'admin';
    case User = 'user';

    public function title(): string
    {
        return match ($this) {
            static::Admin => 'Admin',
            static::User => 'User',
        };
    }
}
