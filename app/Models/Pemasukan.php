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
        'id' 
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class, 'id'); 
    }
}
