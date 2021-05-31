<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    use HasFactory;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'message_id',
        'is_spam',
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public $casts = [
        'is_spam' => 'boolean',
    ];
}
