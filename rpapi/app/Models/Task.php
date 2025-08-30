<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task_i_information_table';

    protected $primaryKey = 'task_i_information_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_name',
        'description',
        'task_category',
        's_bpartner_employee_id',
        'created_by',
        'created_date',
        'is_active',
        'task_status',
    ];

    public function DueDates()
    {
        return $this->hasMany(TaskDueDate::class, 'task_i_information_id', 'task_i_information_id');
    }
    public function user()
    {
        return $this->belongsTo(UserAccount::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
    public function complete()
    {
        return $this->belongsTo(Task::class, 'task_i_information_id', 'task_i_information_id');
    }
    public function scopeFilter($query, $filters)
    {
        if (!empty($filters['assigned'])) {
            $query->where('s_bpartner_employee_id', $filters['assigned']);
        }

        if (!empty($filters['type'])) {
            $query->where('task_category', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('task_status', $filters['status']);
        }

        return $query;
    }
}
