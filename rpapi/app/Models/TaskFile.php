<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFile extends Model
{
    protected $table = 'task_i_file_table';

    protected $primaryKey = 'task_file_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'task_i_information_id',
        'task_file_name',
        'file',
        'is_Active',
        'created_by',
        'created_date',
    ] ;
}
