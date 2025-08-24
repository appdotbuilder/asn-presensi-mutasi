<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Opd;
use App\Models\AsnProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create OPDs first
        $this->call([
            OpdSeeder::class,
        ]);

        // Get OPDs for user assignment
        $bkpsdm = Opd::where('code', 'BKPSDM')->first();
        $disdik = Opd::where('code', 'DISDIK')->first();
        $dinkes = Opd::where('code', 'DINKES')->first();

        // Create Admin User (BKPSDM)
        $admin = User::create([
            'name' => 'Admin BKPSDM',
            'email' => 'admin@bkpsdm.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nip' => '198001012000031001',
            'phone' => '081234567890',
            'opd_id' => $bkpsdm?->id,
            'email_verified_at' => now(),
        ]);

        // Create OPD Operator Users
        $operatorDisdik = User::create([
            'name' => 'Operator Disdik',
            'email' => 'operator@disdik.go.id',
            'password' => Hash::make('password'),
            'role' => 'operator_opd',
            'nip' => '198002012000032001',
            'phone' => '081234567891',
            'opd_id' => $disdik?->id,
            'email_verified_at' => now(),
        ]);

        $operatorDinkes = User::create([
            'name' => 'Operator Dinkes',
            'email' => 'operator@dinkes.go.id',
            'password' => Hash::make('password'),
            'role' => 'operator_opd',
            'nip' => '198003012000033001',
            'phone' => '081234567892',
            'opd_id' => $dinkes?->id,
            'email_verified_at' => now(),
        ]);

        // Create ASN Users
        $asn1 = User::create([
            'name' => 'Budi Santoso, S.Pd',
            'email' => 'budi@disdik.go.id',
            'password' => Hash::make('password'),
            'role' => 'asn',
            'nip' => '199001012020121001',
            'phone' => '081234567893',
            'opd_id' => $disdik?->id,
            'email_verified_at' => now(),
        ]);

        $asn2 = User::create([
            'name' => 'Siti Aisyah, SKM',
            'email' => 'siti@dinkes.go.id',
            'password' => Hash::make('password'),
            'role' => 'asn',
            'nip' => '199002012020122001',
            'phone' => '081234567894',
            'opd_id' => $dinkes?->id,
            'email_verified_at' => now(),
        ]);

        // Create ASN Profiles
        AsnProfile::create([
            'user_id' => $asn1->id,
            'full_name' => 'Budi Santoso, S.Pd',
            'birth_date' => '1990-01-01',
            'birth_place' => 'Jakarta',
            'gender' => 'male',
            'address' => 'Jl. Pendidikan No. 123, Jakarta',
            'position' => 'Guru PNS',
            'rank' => 'III/a',
            'grade' => 'Penata Muda',
            'appointment_date' => '2020-01-01',
            'education_level' => 'S1',
            'major' => 'Pendidikan Matematika',
        ]);

        AsnProfile::create([
            'user_id' => $asn2->id,
            'full_name' => 'Siti Aisyah, SKM',
            'birth_date' => '1990-02-01',
            'birth_place' => 'Bandung',
            'gender' => 'female',
            'address' => 'Jl. Kesehatan No. 456, Bandung',
            'position' => 'Tenaga Kesehatan Masyarakat',
            'rank' => 'III/b',
            'grade' => 'Penata Muda Tingkat I',
            'appointment_date' => '2020-02-01',
            'education_level' => 'S1',
            'major' => 'Kesehatan Masyarakat',
        ]);

        // Create test user (for backward compatibility)
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'asn',
            'nip' => '199999999999999999',
            'phone' => '081234567899',
            'opd_id' => $disdik?->id,
            'email_verified_at' => now(),
        ]);
    }
}
