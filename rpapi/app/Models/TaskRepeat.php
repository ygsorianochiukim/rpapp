<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRepeat extends Model
{
    protected $table = "task_i_repeats_table";

    protected $primaryKey = "task_repeat_id";

    protected $fillable = [
        'task_i_information_id',
        'repeat_frequency',
        'is_Active',
        'created_by',
        'created_date',
    ];
}
