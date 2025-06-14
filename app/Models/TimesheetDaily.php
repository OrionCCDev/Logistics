<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimesheetDaily extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'timesheet_dailies';

    protected $fillable = [
        'user_id',
        'project_id',
        'vehicle_id',
        'date',
        'working_start_hour',
        'working_end_hour',
        'break_duration_minutes',
        'working_hours',
        'odometer_start',
        'odometer_ends',
        'fuel_consumption_status',
        'fuel_consumption',
        'deduction_amount',
        'note',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'working_start_hour' => 'datetime',
        'working_end_hour' => 'datetime',
        'working_hours' => 'decimal:2',
        'odometer_start' => 'decimal:2',
        'odometer_ends' => 'decimal:2',
        'fuel_consumption' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the timesheet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the timesheet.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the vehicle that owns the timesheet.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the approver of the timesheet.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include timesheets for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include timesheets for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include timesheets with a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set default values when creating
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = 'draft';
            }
            if (empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }
}
