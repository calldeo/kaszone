<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengeluaran extends Model
{
    protected $table = 'datapengeluaran';

    protected $fillable = [
        'name',
        'description',
        'date',
        'jumlah',
        'id' // Pastikan nama kolom ini sesuai dengan foreign key
    ];

    public function category() // Gunakan nama relasi yang benar di sini
    {
        return $this->belongsTo(Category::class, 'id'); // Hubungkan dengan model Category
    }
}