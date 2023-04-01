<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'preparacion',
        'ingredientes',
        'imagen',
        'categoria_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaReceta::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');    //'user_id' es el FK de esta tabla 'recetas
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes_receta');
    }
}
