<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'message_id',
        'correct_value'
    ];

    /**
     * Undocumented variable
     *
     * @var array
     */
    public $casts = [
        'correct_value' => 'boolean',
    ];
}
