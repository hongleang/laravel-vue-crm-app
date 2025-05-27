<?php

namespace App\Enums;

enum RolesEnum: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Sales = 'Sales';

    public function title(): string
    {
        return match ($this) {
            static::Admin => 'Admin',
            static::Manager => 'Manager',
            static::Sales => 'Sales',
        };
    }
}
