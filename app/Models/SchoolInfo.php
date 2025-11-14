<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolInfo extends Model
{
    protected $table = 'school_infos';
    use HasFactory;
    protected $fillable = ['name', 'email','phone','session','term', 'status'];
}
