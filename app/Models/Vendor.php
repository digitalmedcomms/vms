<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $table = 'tbl_vendors';
    public $timestamps = false; // Using custom datetime fields

    protected $fillable = [
        'name',
        'logo',
        'country_id',
        'vendor_type_id',
        'address',
        'tin',
        'contact_person',
        'email_address',
        'contact_number',
        'contact_person_2',
        'email_address_2',
        'contact_number_2',
        'status',
        'website_url',
        'created_by',
        'created_when',
        'updated_by',
        'updated_when',
        'contacts',
    ];

    protected $casts = [
        'contacts' => 'array',
    ];

    public function setLogoAttribute($value)
    {
        // Only upload when a real file is provided; skip empty/null to preserve the existing logo
        if ($value && $value instanceof \Illuminate\Http\UploadedFile) {
            $attribute_name = "logo";
            $disk = "public";
            $destination_path = "vendors/logos";

            $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path);
        }
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function type()
    {
        return $this->belongsTo(VendorType::class, 'vendor_type_id');
    }

    public function comments()
    {
        return $this->hasMany(VendorComment::class, 'vendor_id');
    }

    public function getAverageRatingAttribute()
    {
        return number_format($this->comments()->avg('rating') ?: 0, 1);
    }
}
