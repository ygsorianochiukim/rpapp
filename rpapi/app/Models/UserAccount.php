<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserAccount extends Authenticatable implements AuthenticatableContract
{
    use HasApiTokens, Notifiable;

    protected $table = 's_bpartner_employee_table';

    protected $primaryKey = 's_bpartner_employee_id';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'companyname',
        'sex',
        'employee_no',
        'marital_status',
        'birthdate',
        'image_location',
        'remaining_leave',
        'created',
        'date_created',
        'date_updated',
        'updated',
        'is_active',
        's_bpartner_id',
        's_bpartner_employee_group_id',
        's_bpartner_employee_id_apprived1st',
        's_bpartner_employee_id_apprived2nd',
        'sss_no',
        'hdmf_no',
        'phic_no',
        'tin_no',
        'contact_no',
        'address',
        's_bpartner_employee_id_revision',
        'is_for_approval',
        'email',
        'username',
        'password',
        'paf_i_company_id',
        'created_by',
        'created_by',
        'player_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'birthdate' => 'date',
        'is_active' => 'boolean',
    ];

    public function userAccess()
    {
        return $this->hasOne(UserAccess::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
    public function accessLines()
    {
        return $this->hasMany(AccessLine::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }
    public function complete()
    {
        return $this->belongsTo(TaskLogs::class, 's_bpartner_employee_id', 's_bpartner_employee_id');
    }
    public function completedUser()
    {
        return $this->hasMany(TaskLogs::class, 's_bpartner_employee_id', 's_bpartner_employee_id')
                    ->where('is_active', 1);
    }
}
