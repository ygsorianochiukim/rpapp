<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RemindTask extends Model
{
    protected $table = "tasks_i_remind_table";

    protected $primaryKey = "remind_task_id";

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_i_information_id',
        'remind_task',
        'date_remind',
        'is_Active',
        'created_by',
        'created_date',
    ];
}
