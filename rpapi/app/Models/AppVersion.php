<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
     use HasFactory;
    protected $table = 'app_i_versions_table';    
    protected $fillable = [
        'platform',
        'min_version',
        'latest_version',
    ];
}
