<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    protected $table ='emp_i_user_access';

    protected $primaryKey ='emp_i_user_access_id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'emp_i_user_access_id',
        's_bpartner_employee_id',
        'position_id',
        'is_active',
        'date_created',
        'created_by',
    ];
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function user()
    {
        return $this->belongsTo(UserAccount::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
    public function accessLines()
    {
        return $this->hasMany(AccessLine::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
}
