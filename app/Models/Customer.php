<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Auditable;
use App\Models\Audit;

class Customer extends Model
{
    use HasFactory;
    use Auditable;
    protected $guarded = ['id'];

    protected $attributes = [
        'top' => '1',
        'status' => '0',
    ];

    protected $auditParent = null;

    public function setAuditParent($parent)
    {
        $this->auditParent = $parent;
        return $this;
    }

    public function getAuditParentId()
    {
        return $this->auditParent
            ? $this->auditParent->getKey()
            : null;
    }

    public function getAuditParentType()
    {
        return $this->auditParent
            ? get_class($this->auditParent)
            : null;
    }





    // Accessors & Mutators
    public function names(): Attribute
    {
        return new Attribute(
            get: fn($value) => Str::title($value),
            set: fn($value) => Str::lower($value)
        );
    }

    public function email(): Attribute
    {
        return new Attribute(
            set: function ($value) {
                if ($value != null && $value !== '') {
                    return Str::lower($value);
                }

                return null;
            }
        );
    }

    // Appends
    public function formatNoIdentification(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->identification_document_id === IdentificationDocument::NIT) {
                    return $this->no_identification . '-' . $this->dv;
                }
                return $this->no_identification;
            }
        );
    }

    // Relationships
    public function identificationDocument()
    {
        return $this->belongsTo(IdentificationDocument::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->select(['id', 'no_identification', 'names', 'phone'])->find(1);
    }



    public function audits()
    {
        return $this->morphMany(Audit::class, 'parent')
            ->orderByDesc('created_at');
    }

    public function ownAudits()
    {
        return $this->morphMany(Audit::class, 'auditable')
            ->orderByDesc('created_at');
    }
}
