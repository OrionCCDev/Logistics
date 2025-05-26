<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\InvoiceItem;

class Invoice extends Model
{
    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'submission_date',
        'invoice_from_date',
        'invoice_to_date',
        'status',
        'invoice_file_path',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'po_number',
        'notes',
        'invoice_file_path',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'invoice_from_date' => 'date',
        'invoice_to_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'invoice_vehicle')
            ->withPivot('total_hours', 'total_cost_without_tax', 'total_cost_with_tax');
    }

    /**
     * Get the items for the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
