<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'status',
        'due_date',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
