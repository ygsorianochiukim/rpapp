<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLine extends Model
{
    protected $table = 'emp_i_access_line';

    protected $primaryKey = 'emp_i_access_line_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        's_bpartner_employee_id',
        'access_type',
        'date_created',
        'is_active',
        'created_by',
    ];

    public function User()
    {
        return $this->hasMany(UserAccount::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
}
