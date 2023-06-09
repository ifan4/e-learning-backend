<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'materi_id', 'question', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'answer'
    ];

    public function materi(): BelongsTo
    {
        return $this->belongsTo(Materi::class, 'materi_id', 'id');
    }
}
