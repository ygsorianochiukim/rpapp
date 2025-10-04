<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashBorrow extends Model
{
    protected $table = 'cash_i_borrow_table';

    protected $primaryKey = 'cash_i_borrow_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        's_bpartner_employee_id',
        'request_business_unit',
        'payee_information',
        'project_title_purpose',
        'house_hold_expenses',
        'is_active',
        'created_by',
        'created_date',
    ];
}
