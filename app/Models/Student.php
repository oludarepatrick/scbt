<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table ='student';
    protected $fillable = [
        'sn',
        'student_id',
        'surname',
        'phone',
        'firstname',
        'othername',
        'dob',
        'sex',
        'class',
        'class_division',
        'session',
        'state',
        'status',
        'username',
        'password',
        'visible_password',
        'payment_status'
    ];

    
}
