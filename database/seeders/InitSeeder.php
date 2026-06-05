<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institution;
use Spatie\Permission\Models\Role;

class InitSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat 5 Role
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'inspektorat']);
        Role::create(['name' => 'operator_inspektorat']);
        Role::create(['name' => 'operator_dinas']);

        // 2. Buat Instansi Dasar
        $brida = Institution::create(['name' => 'Badan Riset dan Inovasi Daerah Kota Makassar', 'alias' => 'BRIDA', 'status' => 'aktif']);
        $inspektorat = Institution::create(['name' => 'Inspektorat Daerah Kota Makassar', 'alias' => 'INSPEKTORAT', 'status' => 'aktif']);
        Institution::create(['name' => 'Dinas Komunikasi dan Informatika', 'alias' => 'DISKOMINFO', 'status' => 'aktif']);

        // 3. Buat Akun Super Admin
        $superAdmin = User::create([
            'name' => 'Fadehl Thristansyah',
            'email' => 'admin@brida.makassar.go.id',
            'password' => bcrypt('password123'),
            'institution_id' => $brida->id
        ]);
        $superAdmin->assignRole('super_admin');

        // 4. Buat Akun Operator Inspektorat (Untuk test Assign Dinas)
        $evaluator = User::create([
            'name' => 'Andi Evaluator',
            'email' => 'andi@inspektorat.makassar.go.id',
            'password' => bcrypt('password123'),
            'institution_id' => $inspektorat->id
        ]);
        $evaluator->assignRole('operator_inspektorat');
    }
}