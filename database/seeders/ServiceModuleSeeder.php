<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceModuleSeeder extends Seeder
{
    public function run(): void
    {
        // 50 Equipment types (ejemplos comunes)
        $equipmentTypes = [
            'portatil','laptop','desktop','notebook','macbook','chromebook','ipad','tablet',
            'iphone','smartphone','samsung_galaxy','xiaomi','huawei','motorola','lg_phone',
            'playstation','ps4','ps5','xbox','xbox360','xbox_one','nintendo_switch',
            'smart_tv','led_tv','oled_tv','monitor','impresora','multifuncional','router',
            'modem','camaras_dslr','camaras_web','disco_duro_externo','ssd','hdd_internal',
            'motherboard','source_psu','fuente','tarjeta_grafica','gpu','memoria_ram',
            'microfono','altavoz','auriculares','reproductor_bluray','scanner','proyector',
            'lavadora','refrigerador','aire_acondicionado','consola_portatil'
        ];

        // 50 Brands (ejemplos)
        $brands = [
            'Apple','Samsung','Huawei','Xiaomi','Sony','LG','Acer','Asus','Lenovo','HP',
            'Dell','Microsoft','Nokia','Motorola','OnePlus','Google','Realme','Oppo','Vivo','Toshiba',
            'Panasonic','Philips','Sharp','Siemens','Intel','AMD','Nvidia','Kingston','Seagate','Western Digital',
            'SanDisk','Cisco','TP-Link','Brother','Canon','Epson','Ricoh','Blackberry','Alcatel','ZTE',
            'Razer','MSI','BenQ','Hisense','Sanyo','Haier','Bosh','Whirlpool','Electrolux'
        ];

        // Components pool
        $components = [
            'disco duro','ssd','memoria RAM','placa madre','fuente','tarjeta grafica','pantalla',
            'bateria','teclado','touchpad','camara','microfono','altavoz','boton power',
            'lector dvd','ranura sd','antena wifi','placa de audio','chasis','carcasa','ventilador',
            'control remoto','cable hdmi','conector usb','lector sim','rotor','sensor','placa de carga',
            'accesorio carga','pin de carga','microsd reader','modulo bluetooth','cpu','chipset',
            'sensor camara','control de motor','panel tactil','ink cartridge','pump','compressor',
            'display lcd','backlight','inverter','socket','heatpipe','coil','transistor'
        ];

        // Service states (usando tu lista)
        $states = [
            ['key'=>'en_revision','name'=>'En revisión', 'order'=>1],
            ['key'=>'listo','name'=>'Listo', 'order'=>2],
            ['key'=>'abandonado','name'=>'Abandonado', 'order'=>3],
            ['key'=>'devolucion','name'=>'Devolución', 'order'=>4],
            ['key'=>'entregado','name'=>'Entregado', 'order'=>5],
            ['key'=>'listo_para_entregar','name'=>'Listo para entregar', 'order'=>6],
            ['key'=>'pendiente_autorizar','name'=>'Pendiente por autorizar', 'order'=>7],
            ['key'=>'pendiente_parte','name'=>'Pendiente por parte', 'order'=>8],
        ];

        // Insert equipment types
        foreach ($equipmentTypes as $et) {
            DB::table('equipment_types')->insert([
                'name' => Str::title(str_replace('_', ' ', $et)),
                'slug' => Str::slug($et),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert brands
        foreach ($brands as $b) {
            DB::table('brands')->insert([
                'name' => $b,
                'slug' => Str::slug($b),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert components
        foreach ($components as $c) {
            DB::table('components')->insert([
                'name' => Str::title($c),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert states
        foreach ($states as $s) {
            DB::table('service_states')->insert([
                'key' => $s['key'],
                'name' => $s['name'],
                'order' => $s['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Link some components to equipment types (automático y representativo)
        $allEquipmentTypeIds = DB::table('equipment_types')->pluck('id')->toArray();
        $allComponentIds = DB::table('components')->pluck('id')->toArray();

        // For each equipment type, attach a random selection of 1-4 components
        foreach ($allEquipmentTypeIds as $etId) {
            $sample = (array) array_rand(array_flip($allComponentIds), rand(1, 4)); // picks IDs
            foreach ($sample as $compId) {
                DB::table('equipment_type_component')->insert([
                    'equipment_type_id' => $etId,
                    'component_id' => $compId,
                    'default_quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Optional: create a default "Sin asignar" terminal if terminals table exists
        if (Schema::hasTable('terminals')) {
            $firstTerminalId = DB::table('terminals')->value('id');
            if (!$firstTerminalId) {
                $tid = DB::table('terminals')->insertGetId([
                    'name' => 'Sin asignar',
                    'numbering_range_id' => 1, // si no existe, quizá falle — revisar
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
