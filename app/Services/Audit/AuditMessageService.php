<?php

namespace App\Services\Audit;

use Illuminate\Support\Arr;
use App\Services\Audit\AuditDiffService;

class AuditMessageService
{

    protected static array $ignoredFields = [
        'updated_at',
        'created_at',
        'id',
        'closing_date',
        \App\Models\Service::class => ['internal_note'],
    ];

    public static function eventLabel(string $event): string
    {
        return match ($event) {
            'created' => 'CREACIÓN',
            'updated' => 'ACTUALIZACIÓN',
            'deleted' => 'ELIMINACIÓN',
            default   => strtoupper($event),
        };
    }


    protected static array $relationMessages = [

        \App\Models\ServiceProduct::class => [
            'created' => 'Se agregó el producto :name',
            'deleted' => 'Se eliminó el producto :name',
            'resolver' => 'product_id',
            'model' => \App\Models\Product::class,
            'field' => 'name',
        ],

        \App\Models\ServiceDetail::class => [
            'created' => 'Se agregó el componente :name',
            'deleted' => 'Se eliminó el componente :name',
            'model' => \App\Models\Component::class,
            'resolver' => 'component_id',
            'field' => 'name',
        ],

        \App\Models\ServicePayment::class => [
            'created' => 'Se registró un abono de :value',
            'deleted' => 'Se eliminó un abono de :value',
            'resolver' => 'amount',
            'format' => 'currency',
        ],
        \App\Models\ServiceAttachment::class => [
            'created' => 'Se registró una imagen :value',
            'deleted' => 'Se eliminó una imagen :value',
            'resolver' => 'filename'
        ]
    ];

    protected static array $relationsMap = [
        'brand_id' => [
            'model' => \App\Models\Brand::class,
            'label' => 'Marca',
            'field' => 'name',
        ],
        'responsible_id' => [
            'model' => \App\Models\User::class,
            'label' => 'Responsable',
            'field' => 'name',
        ],
        'tech_assigned_id' => [
            'model' => \App\Models\User::class,
            'label' => 'Técnico asignado',
            'field' => 'name',
        ],
        'equipment_type_id' => [
            'model' => \App\Models\EquipmentType::class,
            'label' => 'Tipo de equipo',
            'field' => 'name',
        ],
        'customer_id' => [
            'model' => \App\Models\Customer::class,
            'label' => 'Cliente',
            'field' => 'names',
        ],
        'state_id' => [
            'model' => \App\Models\ServiceState::class,
            'label' => 'Estado',
            'field' => 'name',
        ],
        'terminal_id' => [
            'model' => \App\Models\Terminal::class,
            'label' => 'Sede',
            'field' => 'name',
        ],
        'component_id' => [
            'model' => \App\Models\Component::class,
            'label' => 'Componente',
            'field' => 'name',
        ],
        'identification_document_id' => [
            'model' => \App\Models\IdentificationDocument::class,
            'label' => 'Tipo de Identificacion',
            'field' => 'name',
        ],
        'confirmed_by' => [
            'model' => \App\Models\User::class,
            'label' => 'Usuario que confirmo el cierre',
            'field' => 'name',
        ],
        'user' => [
            'label' => 'Usuario'
        ],
        'document_number' =>  [
            'label' => 'Documento'
        ],
        'model' =>  [
            'label' => 'Modelo'
        ],
        'password' => [
            'label' => 'Contraseña del equipo'
        ],
        'accessories' => [
            'label' => 'Accesorios'
        ],
        'problem_description' =>  [
            'label' => 'Descripcion'
        ],
        'diagnosis' =>  [
            'label' => 'Diagnostico'
        ],
        'estimated_delivery' =>  [
            'label' => 'UsuarFecha de Ingreso'
        ],
        'serial' =>  [
            'label' => 'Serial del equipo'
        ],

        'user' =>  [
            'label' => 'Usuario del equipo'
        ],
        'date_due' =>  [
            'label' => 'Fecha de Vencimiento'
        ],
        'quantity' =>  [
            'label' => 'Cantidad'
        ],
        'capacity' =>  [
            'label' => 'Capacidad'
        ],
        'names' =>  [
            'label' => 'Nombres'
        ],
        'phone' =>  [
            'label' => 'Celular'
        ],
        'neighborhood' =>  [
            'label' => 'Barrio'
        ],
        'top' =>  [
            'label' => 'Destacar'
        ],
        'no_identification' =>  [
            'label' => 'Numero de Identificacion'
        ],
        'direction' =>  [
            'label' => 'Direccion'
        ],
        'confirmed_at' =>  [
            'label' => 'Fecha Confirmación'
        ],
        'price' =>  [
            'label' => 'Precio/Dinero real en caja'
        ],
        'base' =>  [
            'label' => 'Base Inicial'
        ],
        'observations' => ['label' => 'Observaciones']
        ,
        'reason' => ['label' => 'Motivo'],
        'date' => ['label' => 'Fecha']




    ];

    protected static function resolveValue(string $field, $value)
    {
        if (is_null($value)) {
            return '—';
        }

        if (! isset(self::$relationsMap[$field])) {
            return $value;
        }

        $config = self::$relationsMap[$field];

        if (! isset($config['model'])) {
            return $value;
        }
        return optional(
            $config['model']::find($value)
        )->{$config['field']} ?? '—';
    }

    public static function message($audit): array
    {
        $messages = [];

        if (in_array($audit->event, ['created', 'deleted'])) {
            return self::creationDeletionMessage($audit);
        }

        $oldValues = $audit->old_values ?? [];
        $newValues = $audit->new_values ?? [];

        foreach ($newValues as $field => $newValue) {

            if (in_array($field, self::$ignoredFields, true)) {
                continue;
            }
            $oldValue = Arr::get($oldValues, $field);

            if ($oldValue == $newValue) {
                continue;
            }

            $label = self::$relationsMap[$field]['label']
                ?? ucfirst(str_replace('_', ' ', $field));

            $messages[] = [
                'label' => $label,
                'old'   => self::resolveValue($field, $oldValue),
                'new'   => self::resolveValue($field, $newValue),
            ];
        }

        return $messages;
    }

    protected static function creationDeletionMessage($audit): array
    {
        $config = self::$relationMessages[$audit->auditable_type] ?? null;

        if (!$config) {
            return [];
        }

        $data = $audit->event === 'created'
            ? ($audit->new_values ?? [])
            : ($audit->old_values ?? []);

        $value = null;

        if (isset($config['model'])) {
            $model = $config['model']::find($data[$config['resolver']] ?? null);
            $value = $model?->{$config['field']} ?? 'N/D';
        } else {
            $value = $data[$config['resolver']] ?? 'N/D';
        }

        if (($config['format'] ?? null) === 'currency') {
            $value = '$' . number_format($value, 0, ',', '.');
        }

        return [[
            'label' => '',
            'old' => null,
            'new' => str_replace(
                ':name',
                $value,
                str_replace(':value', $value, $config[$audit->event])
            ),
        ]];
    }
}
