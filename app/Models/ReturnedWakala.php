<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedWakala extends Model
{
    use HasFactory;

    protected $table = "returned_wakala";

    protected $fillable = [
        'wakala_id',
        'quantity',
        'single_price',
        'price',
        'created_at',
        'updated_at',
        'user'
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wakalas()
    {
        return $this->hasMany(Wakala::class);
    }

}