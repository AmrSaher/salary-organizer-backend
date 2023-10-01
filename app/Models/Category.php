<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'icon',
        'user_id',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
