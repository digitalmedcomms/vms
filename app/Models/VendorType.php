<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'tbl_vendor_types';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
