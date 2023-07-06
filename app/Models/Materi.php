<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'class_id', 'title', 'description', 'file_materi', 'video_materi'
    ];

    public function Class(): BelongsTo
    {
        return $this->belongsTo(Class_model::class, 'class_id', 'id');
    }

    public function Quizzes(): HasMany
    {
        return $this->hasMany(quiz::class, 'materi_id', 'id');
    }
}
