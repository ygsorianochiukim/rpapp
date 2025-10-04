<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $table = 'documents_i_information_table';

    protected $primaryKey = 'documents_i_information_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable=[
        'doc_title',
        'doc_link',
        'is_active',
        'created_by',
        'created_date',
    ];
}
