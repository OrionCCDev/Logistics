<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'vehicle_model',
        'vehicle_year',
        'vehicle_image',
        'vehicle_status',
        'vehicle_lpo_number',
        'vehicle_lpo_document',
        'vehicle_mulkia_document',
        'supplier_id',
        'operator_id',
    ];


    public function operators()
    {
        return $this->hasMany(Operator::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_vehicle');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function projectVehicles()
    {
        return $this->hasMany(ProjectVehicle::class);
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(Invoice::class, 'invoice_vehicle');
    }

    public function timesheetDailies()
    {
        return $this->hasMany(TimesheetDaily::class)->orderBy('date', 'desc');
    }
}
