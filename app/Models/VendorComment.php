<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorComment extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'tbl_vendor_comments';
    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'rating',
        'comment',
        'insert_date',
        'user_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'userId');
    }
}
