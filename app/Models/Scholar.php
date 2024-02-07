<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ScholarStatus;
use App\Models\User;
use App\Models\ScholarshipCateg;
use App\Models\RenewalDocument;

class Scholar extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'user_id',
        'scholarship_categ_id',
        'scholar_photo_filepath',
        'gender',
        'religion',
        'birthdate',
        'birthplace',
        'civil_status',
        'num_fam_mem',
        'school_yr_started',
        'school_yr_graduated',
        'school_id',
        'home_visit_sched',
        'program',
        'home_visit_sched',
        'fb_account',
        'street',
        'zip_code',
        'region_name',
        'province_name',
        'cities_municipalities_name',
        'barangay_name',
        'scholar_status_id',
    ];


    public function scholarStatus()
    {
        return $this->belongsTo(ScholarStatus::class, 'scholar_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarship_categs()
    {
        return $this->belongsTo(ScholarshipCateg::class, 'scholarship_categ_id');
    }

    public function renewal_documents()
    {
        return $this->hasMany(RenewalDocument::class);
    }

    public function getScholarStatusNameAttribute()
    {
        return $this->scholarStatus->scholar_status_name;  
    }

    public function getUserFirstNameAttribute()
    {
        return $this->user->first_name;
    }

    public function getUserLastNameAttribute()
    {
        return $this->user->last_name;
    }

    public function getUserMiddleNameAttribute()
    {
        return $this->user->middle_name;
    }
    public function getUserEmailAddressAttribute()
    {
        return $this->user->email_address;
    }
    public function getUserMobileNumAttribute()
    {
        return $this->user->user_mobile_num;
    }

    public function getScholarshipCategNameAttribute()
    {
        return $this->scholarship_categs->scholarship_categ_name;
    }



    protected $guarded;
}
