<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Class_model extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description'
    ];

    protected $table = "classes";


    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'class_id', 'id');
    }
}
