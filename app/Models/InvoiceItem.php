<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'vehicle_id',
        'working_hours',
        'unit_price',
        'total',
        'description',
    ];

    /**
     * Get the invoice that owns the item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the vehicle associated with the invoice item.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
