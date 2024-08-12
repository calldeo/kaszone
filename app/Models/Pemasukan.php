<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    protected $table = 'datapemasukan';
    protected $primaryKey = 'id_data';
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
