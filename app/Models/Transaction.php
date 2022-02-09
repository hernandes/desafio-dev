<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{

    protected $fillable = [
        'type', 'transaction_at', 'value', 'document', 'card'
    ];

    protected $casts = [
        'transaction_at' => 'datetime',
        'value' => 'float'
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

}
