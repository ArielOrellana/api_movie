<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actors extends Model
{
    protected $table = 'actors';

    protected $primaryKey='id';

    protected $fillable=[
        'name',
        'created_at',
        'updated_at',
    ];
}
