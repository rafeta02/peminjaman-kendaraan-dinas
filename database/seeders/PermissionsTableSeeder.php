<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'user_alert_create',
            ],
            [
                'id'    => 20,
                'title' => 'user_alert_show',
            ],
            [
                'id'    => 21,
                'title' => 'user_alert_delete',
            ],
            [
                'id'    => 22,
                'title' => 'user_alert_access',
            ],
            [
                'id'    => 23,
                'title' => 'master_access',
            ],
            [
                'id'    => 24,
                'title' => 'sopir_create',
            ],
            [
                'id'    => 25,
                'title' => 'sopir_edit',
            ],
            [
                'id'    => 26,
                'title' => 'sopir_show',
            ],
            [
                'id'    => 27,
                'title' => 'sopir_delete',
            ],
            [
                'id'    => 28,
                'title' => 'sopir_access',
            ],
            [
                'id'    => 29,
                'title' => 'kendaraan_create',
            ],
            [
                'id'    => 30,
                'title' => 'kendaraan_edit',
            ],
            [
                'id'    => 31,
                'title' => 'kendaraan_show',
            ],
            [
                'id'    => 32,
                'title' => 'kendaraan_delete',
            ],
            [
                'id'    => 33,
                'title' => 'kendaraan_access',
            ],
            [
                'id'    => 34,
                'title' => 'pinjam_create',
            ],
            [
                'id'    => 35,
                'title' => 'pinjam_edit',
            ],
            [
                'id'    => 36,
                'title' => 'pinjam_show',
            ],
            [
                'id'    => 37,
                'title' => 'pinjam_delete',
            ],
            [
                'id'    => 38,
                'title' => 'pinjam_access',
            ],
            [
                'id'    => 39,
                'title' => 'log_pinjam_create',
            ],
            [
                'id'    => 40,
                'title' => 'log_pinjam_edit',
            ],
            [
                'id'    => 41,
                'title' => 'log_pinjam_show',
            ],
            [
                'id'    => 42,
                'title' => 'log_pinjam_delete',
            ],
            [
                'id'    => 43,
                'title' => 'log_pinjam_access',
            ],
            [
                'id'    => 44,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
