<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskNotes extends Model
{
    protected $table = "task_i_notes_table";  

    protected $primaryKey = "task_note_id";

    protected $fillable = [
        'task_i_information_id',
        'task_note',
        'is_Active',
        'created_by',
        'created_date',
    ];
}
