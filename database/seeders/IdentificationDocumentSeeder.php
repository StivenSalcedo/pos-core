<?php

namespace Database\Seeders;

use App\Models\IdentificationDocument;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentificationDocumentSeeder extends Seeder
{
    public function run()
    {
        $identifications = [
            [
                'code' => '11',
                'name' => 'Registro civil',
                'is_enabled' => 1,
            ],
            [
                'code' => '12',
                'name' => 'Tarjeta de identidad',
                'is_enabled' => 1,
            ],
            [
                'code' => '13',
                'name' => 'Cédula de ciudadanía',
                'is_enabled' => 1,
            ],
            [
                'code' => '21',
                'name' => 'Tarjeta de extranjería',
                'is_enabled' => 1,
            ],
            [
                'code' => '22',
                'name' => 'Cédula de extranjería',
                'is_enabled' => 1,
            ],
            [
                'code' => '31',
                'name' => 'NIT',
                'is_enabled' => 1,
            ],
            [
                'code' => '41',
                'name' => 'Pasaporte',
                'is_enabled' => 1,
            ],
            [
                'code' => '42',
                'name' => 'Documento de identificación extranjero',
                'is_enabled' => 1,
            ],
            [
                'code' => '47',
                'name' => 'PEP',
                'is_enabled' => 1,
            ],
            [
                'code' => '50',
                'name' => 'NIT otro país',
                'is_enabled' => 1,
            ],
            [
                'code' => '91',
                'name' => 'NUIP *',
                'is_enabled' => 1,
            ],
            [
                'code' => '48',
                'name' => 'PPT (Permiso Protección Temporal)',
                'is_enabled' => 1,
            ],
        ];

        foreach ($identifications as $identification) {
            IdentificationDocument::create($identification);
        }
    }
}
