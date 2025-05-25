<?php

namespace App\Enums;

enum PermissionEnum: string
{
    const ReadDashboard = 'read dashboard';

    const ReadUser = 'read user';
    const WriteUser = 'write user';
    const DeleteUser = 'delete user';
    const RestoreUser = 'restore user';
}
