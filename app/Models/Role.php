<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'roles_name',
        'roles_description',
    ];
}
