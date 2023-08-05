<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    protected $table = 'movies';

    protected $primaryKey='id';

    public $timestamps = true;

    protected $fillable=[
        'title',
        'genre',
        'director_id',
        'created_at',
        'updated_at',
    ];

}
