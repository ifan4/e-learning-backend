<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'title', 'description', 'file_materi', 'video_materi'
    ];

    public function Class(): BelongsTo
    {
        return $this->belongsTo(Class_model::class, 'class_id', 'id');
    }
}
