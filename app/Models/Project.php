<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'start_date',
        'end_date',
        'status',
        'branch_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_projects');
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }

    public function getFuelConsumptionThisMonthAttribute()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return $this->hasManyThrough(
            TimesheetDaily::class,
            ProjectVehicle::class,
            'project_id',
            'vehicle_id',
            'id',
            'vehicle_id'
        )
        ->whereBetween('timesheet_dailies.date', [$startOfMonth, $endOfMonth])
        ->sum('timesheet_dailies.fuel_consumption');
    }

}
