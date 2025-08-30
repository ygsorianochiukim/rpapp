<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamAccess extends Model
{
    protected $table = "team_i_access_table";

    protected $primaryKey = "team_access_id";

    protected $fillable = [
        's_bpartner_employee_id',
        'supervisor_id',
        'is_active',
        'created_by',
        'created_date',
    ];

    public function user()
    {
        return $this->belongsTo(UserAccount::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }

    public function userAccess()
    {
        return $this->hasOne(UserAccess::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
}
