<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'metadata',
        'handled_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'handled_at' => 'datetime',
    ];
}
