<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episodes extends Model
{
    protected $table = 'episodes';

    protected $primaryKey='id';

    protected $fillable=[
        'tv_show_id',
        'name_episode',
        'season_number',
        'episode_number',
        'created_at',
        'updated_at',
    ];
}
