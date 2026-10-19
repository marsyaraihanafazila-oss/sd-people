<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            'Brand',
            'CRM',
            'Development',
            'Finance',
            'GA',
            'HR',
            'Live Commerce',
            'Marketplace',
            'Operations',
            'Research',
            'Social Media',
        ];

        foreach ($divisis as $namaDivisi) {
            Divisi::updateOrCreate(
                ['nama_divisi' => $namaDivisi],
                []
            );
        }
    }
}
