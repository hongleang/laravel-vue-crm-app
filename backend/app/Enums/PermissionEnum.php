<?php

namespace App\Enums;

enum PermissionEnum: string
{
    const ReadDashboard = 'read dashboard';

    const ReadFile = 'read file';
    const WriteFile = 'write file';

    const ReadUser = 'read user';
    const WriteUser = 'write user';
    const DeleteUser = 'delete user';
    const RestoreUser = 'restore user';

    const ReadCompany = 'read company';
    const WriteCompany = 'write company';
    const DeleteCompany = 'delete company';
    const RestoreCompany = 'restore company';
}
