<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = [
        'iso',
        'name',
        'nicename',
        'iso3',
        'numcode',
        'phonecode',
    ];
}
