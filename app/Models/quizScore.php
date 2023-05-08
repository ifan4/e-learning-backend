<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class quizScore extends Model
{
    use HasFactory;


    protected $fillable = [
        'quiz_id', 'user_id', 'score', 'answer'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(quiz::class, 'quiz_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
