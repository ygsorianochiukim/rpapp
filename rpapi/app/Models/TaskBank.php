<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskBank extends Model
{
    protected $table = 'emp_i_task_bank_table';
    protected $primaryKey = 'task_bank_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_name',
        'description',
        'task_category',
        'position_id',
        'due_date',
        'is_active',
        'created_by',
        'created_date',
        'task_notes',
        'task_step',
        'file',
        'repeat_frequency',
        'remind_task',
        'date_remind',
    ];
    protected $attributes = [
        'is_active' => 1,
    ];

    public $timestamps = false;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_bank_id', 'task_bank_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }
}
