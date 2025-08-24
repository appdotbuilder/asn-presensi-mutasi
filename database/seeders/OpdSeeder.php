<?php

namespace Database\Seeders;

use App\Models\Opd;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpdSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $opds = [
            [
                'name' => 'Dinas Pendidikan',
                'code' => 'DISDIK',
                'description' => 'Dinas Pendidikan dan Kebudayaan',
                'address' => 'Jl. Merdeka No. 123',
                'phone' => '021-1234567',
                'email' => 'disdik@pemda.go.id',
                'status' => 'active',
            ],
            [
                'name' => 'Dinas Kesehatan',
                'code' => 'DINKES',
                'description' => 'Dinas Kesehatan Daerah',
                'address' => 'Jl. Sehat No. 456',
                'phone' => '021-2345678',
                'email' => 'dinkes@pemda.go.id',
                'status' => 'active',
            ],
            [
                'name' => 'Dinas Pekerjaan Umum',
                'code' => 'DPU',
                'description' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'address' => 'Jl. Pembangunan No. 789',
                'phone' => '021-3456789',
                'email' => 'dpu@pemda.go.id',
                'status' => 'active',
            ],
            [
                'name' => 'Dinas Sosial',
                'code' => 'DINSOS',
                'description' => 'Dinas Sosial dan Pemberdayaan Masyarakat',
                'address' => 'Jl. Gotong Royong No. 101',
                'phone' => '021-4567890',
                'email' => 'dinsos@pemda.go.id',
                'status' => 'active',
            ],
            [
                'name' => 'BKPSDM',
                'code' => 'BKPSDM',
                'description' => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
                'address' => 'Jl. Pegawai No. 999',
                'phone' => '021-9999999',
                'email' => 'bkpsdm@pemda.go.id',
                'status' => 'active',
            ],
        ];

        foreach ($opds as $opdData) {
            Opd::create($opdData);
        }
    }
}