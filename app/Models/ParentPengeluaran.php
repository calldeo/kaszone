<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentPengeluaran extends Model
{
    use HasFactory;
     protected $table = 'pengeluaran_parent';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tanggal',
         // Pastikan nama kolom ini sesuai dengan foreign key
    ];

      public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_parent');
    }

    
}
