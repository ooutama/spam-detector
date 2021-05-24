<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        'message',
    ];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function reports() 
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function prediction() 
    {
        return $this->hasOne(Prediction::class)->latest()->limit(1);
    }
}
