<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo_path',
        'trade_license_path',
        'vat_certificate_path',
        'statement_path',
        'contact_name',
        'contact_email',
        'address',
        'description',
        'phone',
        'status',
        'category_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset('dashAssets/uploads/suppliers/logos/' . $this->logo_path) : null;
    }

    public function getTradeLicenseUrlAttribute()
    {
        return $this->trade_license_path ? asset('dashAssets/uploads/suppliers/documents/' . $this->trade_license_path) : null;
    }

    public function getVatCertificateUrlAttribute()
    {
        return $this->vat_certificate_path ? asset('dashAssets/uploads/suppliers/documents/' . $this->vat_certificate_path) : null;
    }

    public function getStatementUrlAttribute()
    {
        return $this->statement_path ? asset('dashAssets/uploads/suppliers/documents/' . $this->statement_path) : null;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
