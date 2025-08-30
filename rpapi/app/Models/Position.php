<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'emp_i_position';

    protected $primaryKey = 'position_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'position',
        'department',
        'function',
        'date_created',
        'is_active',
        'created_by',
    ];

}
