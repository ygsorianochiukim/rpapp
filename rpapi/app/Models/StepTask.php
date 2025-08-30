<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StepTask extends Model
{
    protected $table = "step_i_tasks_table";

    protected $primaryKey = "task_step_id";
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_i_information_id',
        'task_steps_description',
        'is_active',
        'created_by',
        'created_date',
    ];
}
