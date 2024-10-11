<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingSaldo extends Model
{
    use HasFactory;

    protected $table = 'setting_saldo';
    protected $fillable = ['saldo'];    
}   
