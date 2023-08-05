<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
    protected $table = 'tv_shows';

    protected $primaryKey='id';

    protected $fillable=[
        'title',
        'genre',
        'director_id',
        'created_at',
        'updated_at',
    ];
}
