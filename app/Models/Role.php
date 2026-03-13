<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'tbl_roles';
    protected $primaryKey = 'roleId';
    public $timestamps = false;

    protected $fillable = [
        'role',
        'status',
        'isDeleted',
        'createdBy',
        'createdDtm',
        'updatedBy',
        'updatedDtm',
    ];
}
