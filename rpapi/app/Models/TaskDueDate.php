<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDueDate extends Model
{
    protected $table = "task_i_due_dates_table";

    protected $primaryKey = "task_due_date_id";

    protected $fillable = [
        'task_i_information_id',
        'due_date',
        'is_Active',
        'created_by',
        'created_date',
    ];

    public function Task()
    {
        return $this->belongsTo(Task::class, 'task_i_information_id', 'task_i_information_id'); 
    }

}
