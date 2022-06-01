<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'brand_name', 'serial_number', 'model', 'availability', 'date_received', 'date_sold','unit_type'
    ];
}