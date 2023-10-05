<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'cost',
        'user_id',
        'icon',
        'color',
        'isIncome',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
