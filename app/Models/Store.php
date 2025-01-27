<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{

    protected $fillable = [
        'name', 'owner'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

}
