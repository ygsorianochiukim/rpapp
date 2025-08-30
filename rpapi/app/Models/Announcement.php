<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements_i_table';

    protected $primaryKey = 'announcement_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'announcement_description',
        'date_validity',
        'is_active',
        'created_by',
        'created_date',
    ];
}
