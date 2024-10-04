<?php

namespace App\Models;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Jika Anda menggunakan tabel yang tidak sesuai dengan konvenssi plural,
    // Anda bisa menyebutkan nama tabel dengan cara ini.
    protected $table = 'categories';
    protected $primaryKey = 'id';

    // Mengizinkan mass assignment untuk field berikut
    protected $fillable = [
        // 'id',
        'name',
        'jenis_kategori',
        'description',
        
    ];

    public const PemasukanCode = '1';  
    public const PengeluaranCode = '2';  

    // Anda dapat menambahkan attribute yang perlu di-hidden dari serialization di sini
    protected $hidden = [
        // 'example_hidden_attribute',
    ];

    // Jika ada field yang perlu di-cast, tambahkan di sini
    protected $casts = [
        // 'example_date_field' => 'datetime',
    ];
     public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id');
    }
      public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id');
    }
}