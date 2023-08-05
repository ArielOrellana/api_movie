<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directors extends Model
{
    protected $table = 'directors';

    protected $primaryKey='id';

    protected $fillable=[
        'name',
        'created_at',
        'updated_at',
    ];
}
